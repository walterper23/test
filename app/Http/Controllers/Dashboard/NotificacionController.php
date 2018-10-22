<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Events\NuevoDocumentoLocalRecepcionado;
// use App\Events\NuevoDocumentoForaneoRecepcionado;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MNotificacion;
use App\Model\MNotificacionArea;
use App\Model\MSystemNotificacion;

use Exception;

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
        $system_notificacion = cache('System.Notificaciones')->where('SYNO_CODIGO',$codigo_notificacion)->first();

        $notificacion = new MNotificacion;
        $notificacion->NOTI_SYSTEM_NOTIFICACION = $system_notificacion->getKey();
        $notificacion->NOTI_SYSTEM_TIPO         = $system_notificacion->getTipo();
        $notificacion->NOTI_SYSTEM_PERMISO      = $system_notificacion->getPermiso();
        $notificacion->NOTI_COLOR               = $system_notificacion->getColor();
        $notificacion->NOTI_CONTENIDO           = isset($data['contenido']) ? $data['contenido'] : null;
        $notificacion->NOTI_URL                 = isset($data['url']) ? $data['url'] : null;
        $notificacion->NOTI_USUARIOS_VISTO      = [];
        $notificacion->save();

        // En este paso se verifica si la notificación se registrará para alguna dirección o departamento.
        // El tipo de notificación deberá ser tipo 3 => Panel de trabajo.
        if( $notificacion->getTipo() == 3 ) // Panel de trabajo
        {
            $notificacion_area = new MNotificacionArea;
            $notificacion_area->NOAR_NOTIFICACION = $notificacion->getKey();
            $notificacion_area->NOAR_DIRECCION    = $data['direccion'];
            $notificacion_area->NOAR_DEPARTAMENTO = isset($data['departamento']) ? $data['departamento'] : null;
            $notificacion_area->save();
        }

        return true;
    }

    /**
     * Método para agregar a un usuario a la lista de usuarios que han visto la notificación ya que han indicado eliminarla
     */
    public static function eliminarNotificacion( $id_notificacion )
    {
        try {

            $notificacion = MNotificacion::findOrFail( $id_notificacion );
            
            $usuarios = $notificacion->getUsuariosVisto() ?? []; // Recuperamos la lista de usuarios

            if (! in_array(userKey(), $usuarios) ) // Verificamos que el usuario no esté en la lista
            {
                array_push($usuarios, userKey()); // Agregamos al usuario a la lista
            }

            $notificacion->NOTI_USUARIOS_VISTO = $usuarios; // Guardamos la nueva lista
            $notificacion->save();

            $instance = new static;
            
            return $instance->responseSuccessJSON();
        } catch(Exception $error) {
            
        }
    }

    public static function enviarCorreoSobreNuevaRecepcion($codigo_preferencia, $documento)
    {
        if( config('app.debug') === false && boolval(config_var('Adicional.Envio.Correo.Prod')) === true )
        {
            $preferencia = cache('System.Preferencias')->where('SYPR_CODIGO',$codigo_preferencia)->first();

            // Buscamos a los usuarios que tengan la preferencia marcada
            $usuarios = $preferencia->Usuarios()->with('UsuarioDetalle')->get();

            // Obtener un array con los correos de los usuarios
            $correos = $usuarios->map(function($usuario){
                return $usuario->UsuarioDetalle->getEmail();
            })->toArray();

            // Si se encontraron correos, procedemos a hacer el envío de la notificación
            if( sizeof($correos) > 0 )
            {
                if ($documento->getTipoRecepcion() == 1) // Recepción local
                {
                    event(new NuevoDocumentoLocalRecepcionado($documento, $correos));
                }
                else
                {
                    event(new NuevoDocumentoForaneoRecepcionado($documento, $correos));
                }
            }
        }
    }
}