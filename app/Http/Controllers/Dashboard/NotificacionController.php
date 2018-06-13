<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Mail;

/* Models */
use App\Model\MNotificacion;

class NotificacionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLog('NotificacionController.log');
    }

    // Método para creación de una nueva notificación
    public static function nuevaNotificacion( $codigo_notificacion, $data )
    {
        switch ($codigo_notificacion) {
            case 'PT.NDR': // Panel de trabajo :: Nuevo documento recibido
                $data['permiso'] = 18;
                $type = 3; // Panel de trabajo
                break;
            case 'PT.DSR': // Panel de trabajo :: Documento con semáforo recibido
                $data['permiso'] = 18;
                $type = 3; // Panel de trabajo
                break;
            case 'RL.NTD': // Recepción local :: Nuevo tipo de documento
                $data['permiso'] = 1;
                $type = 1; // Recepción local
                break;
            case 'RL.TDD': // Recepción local :: Tipo de documento desactivado
                $data['permiso'] = 1;
                $type = 1; // Recepción local
                break;
            case 'RL.TDE': // Recepción local :: Tipo de documento eliminado
                $data['permiso'] = 1;
                $type = 1; // Recepción local
                break;
            case 'RL.DFT': // Recepción local :: Documento foráneo en tránsito
                $data['permiso'] = 1;
                $type = 1; // Recepción local
                break;
            case 'RF.NTD': // Recepción foránea :: Nuevo tipo de documento
                $data['permiso'] = 19;
                break;
            case 'RF.TDD': // Recepción foránea :: Tipo de documento desactivado
                $data['permiso'] = 19;
                break;
            case 'RF.TDE': // Recepción foránea :: Tipo de documento eliminado
                $data['permiso'] = 19;
                break;
            case 'RF.DRL': // Recepción foránea :: Documento validado/recepcionado localmente
                $data['permiso'] = 19;
                break;
            case 'DS.DSR': // Documento semaforizado :: Documento semaforizado respondido
            case 'DS.DSF': // Documento semaforizado :: Documento semaforizado próximo a finalizar
            case 'DS.DSL': // Documento semaforizado :: Documento semáforizado no respondido a tiempo límite
            case 'DS.TLM': // Documento semaforizado :: Tiempo límite modificado para contestación de solicitudes
                $type = 4; // Semaforización de documentos
                $data['permiso'] = 21;
                break;
            case 'VF.NRF': // Ver recepciones foráneas :: Nueva recepción foránea capturada
                $data['permiso'] = 22;
            default:
                # code...
                break;
        }

        return $this -> crearNotificacion( $type, $data );
    }

    public function crearNotificacion( $type, $data )
    {
        $notificacion = new MNotificacion;
        $notificacion -> NOTI_CODIGO    = $type;
        $notificacion -> NOTI_CONTENIDO = $data['contenido'];
        $notificacion -> NOTI_URL       = isset($data['url']) ? $data['url'] : null;
        $notificacion -> NOTI_PERMISO   = $data['permiso'];
        $notificacion -> save();

        return true;
    }

    public function mandarNotificacionCorreo($documento)
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
        });

        $correos = $correos -> toArray();

        $correos = ['rcl6395@gmail.com','notificaciones.sigesd@qroo.gob.mx'];

        if ($documento -> getTipoRecepcion() == 1) // Recepción local
            Mail::to($correos) -> queue( new \App\Mail\NuevoDocumentoRecibido($documento) );
        else
            Mail::to($correos) -> queue( new \App\Mail\NuevoDocumentoForaneoRecibido($documento) );

    }

}