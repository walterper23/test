<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Mail;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MNotificacion;
use App\Model\MNotificacionArea;
use App\Model\MSystemNotificacion;

/**
 * Controlador para la creación de notificaciones a usuarios en el sistema.
 * Envío de correos a usuarios según la notificacion creada.
 */
class NotificacionController extends BaseController
{
    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLog('NotificacionController.log');
    }

    /**
      * Método para creación de una nueva notificación
      */
    public static function nuevaNotificacion( $codigo_notificacion, Array $data )
    {
        $system_notificacion = MSystemNotificacion::where('SYNO_CODIGO',$codigo_notificacion) -> limit(1) -> first();

        $notificacion = new MNotificacion;
        $notificacion -> NOTI_SYSTEM_NOTIFICACION = $system_notificacion -> getKey();
        $notificacion -> NOTI_SYSTEM_TIPO         = $system_notificacion -> getTipo();
        $notificacion -> NOTI_SYSTEM_PERMISO      = $system_notificacion -> getPermiso();
        $notificacion -> NOTI_COLOR               = !array_key_exists('color',$data) ? $system_notificacion -> getColor() : $data['color'] ;
        $notificacion -> NOTI_CONTENIDO           = isset($data['contenido']) ? $data['contenido'] : null;
        $notificacion -> NOTI_URL                 = isset($data['url']) ? $data['url'] : null;
        $notificacion -> save();

        // En este paso se verifica si la notificación se registrará para alguna dirección o departamento.
        // El tipo de notificación deberá ser tipo 3 => Panel de trabajo.
        if( $notificacion -> getTipo() == 3 ) // Panel de trabajo
        {
            $notificacion_area = new MNotificacionArea;
            $notificacion_area -> NOAR_NOTIFICACION = $notificacion -> getKey();
            $notificacion_area -> NOAR_DIRECCION    = $data['direccion'];
            $notificacion_area -> NOAR_DEPARTAMENTO = isset($data['departamento']) ? $data['departamento'] : null;
            $notificacion_area -> save();
        }

        return true;
    }

    public static function mandarNotificacionCorreo($documento)
    {
        $correos = [];
        
        if( config('app.debug') === false && boolval(config_var('Configuracion.Envio.Correo.Prod')) === true )
        {
            if ($documento -> getTipoRecepcion() == 1){ // Recepción local

                if( $documento -> getTipoDocumento() == 1 ) // Denuncia
                {
                    $preferencia = \App\Model\MPreferencia::find(1);
                }
                else if( $documento -> getTipoDocumento() == 2 ) // Documento para denuncia
                {
                    $preferencia = \App\Model\MPreferencia::find(2);
                }
                else // Otro tipo de documento
                {
                    $preferencia = \App\Model\MPreferencia::find(3);
                }
            }
            else
            {
                if( $documento -> getTipoDocumento() == 1 ) // Denuncia
                {
                    $preferencia = \App\Model\MPreferencia::find(4);
                }
                else if( $documento -> getTipoDocumento() == 2 ) // Documento para denuncia
                {
                    $preferencia = \App\Model\MPreferencia::find(5);
                }
                else // Otro tipo de documento
                {
                    $preferencia = \App\Model\MPreferencia::find(6);
                }   
            }

            $usuarios = $preferencia -> Usuarios() -> with('UsuarioDetalle') -> get();

            $correos = $usuarios -> map(function($usuario){
                return $usuario -> UsuarioDetalle -> getEmail();
            }) -> toArray();
        }
        else
        {
            $correos = ['rcl6395@gmail.com','notificaciones.sigesd@qroo.gob.mx'];
        }

        if( sizeof($correos) > 0 )
        {
            if ($documento -> getTipoRecepcion() == 1) // Recepción local
                Mail::to($correos) -> queue( new \App\Mail\NuevoDocumentoRecibido($documento) ); // Mandar notificación sobre documento local
            else
                Mail::to($correos) -> queue( new \App\Mail\NuevoDocumentoForaneoRecibido($documento) ); // Mandar notificación sobre documento foráneo
        }

    }

}