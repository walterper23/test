<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MNotificacion;

class DashboardController extends BaseController
{
    public function __construct(){
        $this -> setLog('DashboardController.log');
    }

    public function index(){

        $data_notificaciones['recepcion_local']   = [];
        $data_notificaciones['recepcion_foranea'] = [];
        $data_notificaciones['panel_trabajo']     = [];
        $data_notificaciones['semaforizacion']    = [];

        $permisos_usuario = user() -> Permisos -> pluck('SYPE_PERMISO') -> toArray();

        $notificaciones = MNotificacion::existente() -> where(function($query){
            $query -> whereNull('NOTI_VISTO_ELIMINADO');
            // faltan agregar mas validaciones
        })
        -> whereIn('NOTI_PERMISO',$permisos_usuario)
        -> get();

        $notificaciones -> map(function($notificacion) use (&$data_notificaciones){

            $data_notificaciones['recepcion_local'][] = $notificacion;

        });

        return view('Dashboard.indexDashboard') -> with($data_notificaciones);
    }

}
