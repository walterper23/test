<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

/* Models */
use App\Model\MNotificacion;

class NotificacionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLog('NotificacionController.log');
    }

    public function index(){

        return view('Dashboard.indexDashboard');
    }


    // Método para creación de una nueva notificación
    public static function nuevaNotificacion( $type, $data )
    {
        switch ($type) {
            case 'PT.NDR': // Panel de trabajo :: Nuevo documento recibido
                $data['permiso'] = 18;
                break;
            case 'PT.DSR': // Panel de trabajo :: Documento con semáforo recibido
                $data['permiso'] = 18;
                break;
            case 'RL.NTD': // Recepción local :: Nuevo tipo de documento
                $data['permiso'] = 1;
                break;
            case 'RL.TDD': // Recepción local :: Tipo de documento desactivado
                $data['permiso'] = 1;
                break;
            case 'RL.TDE': // Recepción local :: Tipo de documento eliminado
                $data['permiso'] = 1;
                break;
            case 'RL.DFT': // Recepción local :: Documento foráneo en tránsito
                $data['permiso'] = 1;
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
                $data['permiso'] = 21;
                break;
            case 'DS.DSF': // Documento semaforizado :: Documento semaforizado próximo a finalizar
                $data['permiso'] = 21;
                break;
            case 'DS.DSL': // Documento semaforizado :: Documento semáforizado no respondido a tiempo límite
                $data['permiso'] = 21;
                break;
            case 'DS.TLM': // Documento semaforizado :: Tiempo límite modificado para contestación de solicitudes
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

}