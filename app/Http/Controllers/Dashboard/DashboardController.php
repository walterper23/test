<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Repositories */
use App\Repositories\DocumentoRepository;

/* Models */
use App\Model\MDocumento;
use App\Model\MNotificacion;

class DashboardController extends BaseController
{
    public function __construct(){
        $this -> setLog('DashboardController.log');
    }

    public function index(Request $request){

        $this -> reporteDocumentos($request);

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


    public function manager(Request $request)
    {
        switch ($request -> action) {
            case 'reporte-documentos' :
                $response = $this -> reporteDocumentos( $request );
                break;
            default:
                abort(404);
                break;
        }

        return $response;
    }

    public function reporteDocumentos($request, DocumentoRepository $documentos)
    {
        $anio_actual = \Carbon\Carbon::now() -> year;
        $documentos = [];
            

        $data = [
            'hoy' => $this -> documentosHoy( $documentos ),
            'semana' => $this -> documentosSemana( $documentos ),
            'mes' => $this -> documentosMes( $documentos ),
            'anual' => $this -> documentosAnual( $documentos )
        ];

        return response() -> json($data);
    }

    public function documentosHoy( $documentos )
    {
        $data = [
            'labels' => [],
            'locales' => [],
            'foraneos' => []
        ];

        return $data;
    }

    public function documentosSemana( $documentos )
    {
        $data['denuncias'] = $documentos -> filter(function($doc){
            if( $doc -> getTipoDocumento() == 1 )
                return $doc;
        }) -> count();

        $data['doctos_denuncias'] = $documentos -> filter(function($doc){
            if( $doc -> getTipoDocumento() == 2 )
                return $doc;
        }) -> count();

        $data['documentos'] = $documentos -> filter(function($doc){
            if( $doc -> getTipoDocumento() > 2 )
                return $doc;
        }) -> count();

        return $data;
    }

    public function documentosMes( $documentos )
    {
        $data = [];


        return $data;
    }

    public function documentosAnual( $documentos )
    {
        $data = [];


        return $data;
    }

}
