<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
//use App\Model\MDocumento;
use App\Model\MUsuario;
//use App\Model\System\MSystemTipoDocumento;

/**
 * Controlador para la página principal del usuario
 */
class DashboardController extends BaseController
{
    protected $documentosRepository;

    public function __construct()
    {
        parent::__construct();
        $this->setLog('DashboardController.log');
    }

    public function index(Request $request)
    {
        if( user()->canAtLeast('REPO.VER.GRA.DIA.SEM','REPO.VER.GRA.MEN.ANUAL') ){

            $fecha_documentos_recibidos_hoy = Carbon::now()->format('d \d\e xm \d\e Y');
            $fecha_documentos_recibidos_hoy = nombreFecha($fecha_documentos_recibidos_hoy);

            $mes_actual = nombreFecha( Carbon::now()->format('xm') );

            $inicio_semana           = Carbon::now()->startOfWeek();
            $fin_semana              = Carbon::now()->endOfWeek();
            $fecha_documentos_semana = sprintf('Lunes %s a Domingo %s', $inicio_semana->format('d'), $fin_semana->format('d \d\e xm') );
            $fecha_documentos_semana = nombreFecha($fecha_documentos_semana);

            return view('Dashboard.indexDashboard', compact('fecha_documentos_recibidos_hoy','fecha_documentos_semana','mes_actual'));

        }
        else if( user()->can('REC.DOCUMENTO.LOCAL') ) // Recepcionista local
        {
            return redirect('recepcion/documentos');
        }
        else if( user()->can('REC.DOCUMENTO.FORANEO') ) // Recepcionista foráneo
        {
            return redirect('recepcion/documentos-foraneos');
        }
        else if( user()->can('SEG.PANEL.TRABAJO') ) // Dirección y/o departamento
        {
            return redirect('panel/documentos');
        }else  if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.CONFIG') ) // Administrador del sistema
        {
            return redirect('configuracion/catalogos');
        }
        else // Pantalla de inicio por default
        {
            return view('Dashboard.indexDefault');
        }
        
    }

    public function manager(Request $request)
    {
        switch ($request->action) {
            case 'get-notificaciones' : 
                $response = $this->getNotificaciones( $request );
                break;
            case 'eliminar-notificacion' : 
                $response = $this->eliminarNotificacion( $request );
                break;
            default:
                abort(404);
                break;
        }

        return $response;
    }


    public function getNotificaciones( $request )
    {
        $data_notificaciones = [];

        $permisos_usuario = user()->Permisos->pluck('SYPE_PERMISO')->toArray();

        $notificaciones_recepcion = MUsuario::select('NOTI_NOTIFICACION','NOTI_SYSTEM_TIPO','NOTI_COLOR','NOTI_CREATED_AT','NOTI_CONTENIDO','NOTI_URL','SYTN_CODIGO','SYTN_NOMBRE')
        ->join('notificaciones',function($query){
            $query->whereRaw('!JSON_CONTAINS(NOTI_USUARIOS_VISTO, CAST(USUA_USUARIO AS JSON),"$")');
        })
        ->join('system_tipos_notificaciones','NOTI_SYSTEM_TIPO','=','SYTN_TIPO')
        ->whereIn('NOTI_SYSTEM_TIPO',[1,2,4])
        ->where('NOTI_DELETED',0)
        ->where('USUA_DELETED',0)
        ->where('USUA_USUARIO',userKey())
        ->whereIn('NOTI_SYSTEM_PERMISO', $permisos_usuario)
        ->orderBy('NOTI_SYSTEM_TIPO','ASC')
        // ->orderBy('NOTI_CREATED_AT','DESC')
        ->get();

        foreach ($notificaciones_recepcion as $notificacion) {
            $data_notificaciones[ $notificacion->NOTI_SYSTEM_TIPO ][] = $notificacion; // NOTI_SYSTEM_TIPO => 1,2,4
        }

        /*****************************************************************/

        // Buscamos las direcciones asignadas al usuario
        $ids_direcciones_usuario = session('DireccionesKeys');
        $ids_departamentos_usuario = session('DepartamentosKeys');
        
        $notificaciones_areas = [];
        if ( sizeof($ids_direcciones_usuario) > 0 || sizeof($ids_departamentos_usuario) > 0 )
        {
            $notificaciones_areas = MUsuario::select('NOTI_NOTIFICACION','NOTI_SYSTEM_TIPO','NOTI_COLOR','NOTI_CREATED_AT','NOTI_CONTENIDO','NOTI_URL','SYTN_CODIGO','SYTN_NOMBRE')
            ->join('notificaciones',function($query){
                $query->whereRaw('!JSON_CONTAINS(NOTI_USUARIOS_VISTO, CAST(USUA_USUARIO AS JSON),"$")');
            })
            ->join('notificaciones_areas','NOTI_NOTIFICACION','=','NOAR_NOTIFICACION')
            ->join('system_tipos_notificaciones','NOTI_SYSTEM_TIPO','=','SYTN_TIPO')
            ->where('NOTI_SYSTEM_TIPO',3)
            ->where('NOTI_DELETED',0)
            ->where('NOAR_ENABLED',1)
            ->where('USUA_DELETED',0)
            ->where('USUA_USUARIO',userKey())
            ->where(function($query) use($ids_direcciones_usuario,$ids_departamentos_usuario){
                $query->whereIn('NOAR_DIRECCION',$ids_direcciones_usuario);
                $query->orWhereIn('NOAR_DEPARTAMENTO',$ids_departamentos_usuario);
            })
            ->orderBy('NOTI_SYSTEM_TIPO','ASC')
            // ->orderBy('NOTI_CREATED_AT','DESC')
            ->get();
        }

        foreach ($notificaciones_areas as $notificacion) {
            $data_notificaciones[ $notificacion->NOTI_SYSTEM_TIPO ][] = $notificacion; // NOTI_SYSTEM_TIPO => 3
        }
        /******************************************************************/

        
        // A continuación, mezclaremos las notificaciones
        $data_notificaciones_mixed = [];
        // dd('<pre>',$data_notificaciones);
        foreach ($data_notificaciones as $tipo_notificacion => $notificaciones) {

            foreach ($notificaciones as $notificacion) {
                $nueva_notificacion = [
                    'id'        => $notificacion->NOTI_NOTIFICACION,
                    'tipo'      => $tipo_notificacion,
                    'fecha'     => $notificacion->NOTI_CREATED_AT,
                    'url'       => $notificacion->NOTI_URL ? url($notificacion->NOTI_URL) : '#',
                    'contenido' => $notificacion->NOTI_CONTENIDO,
                ];
                
                $nueva_notificacion['badge'] = sprintf('<span class="badge badge-%s" title="%s">%s</span>',$notificacion->NOTI_COLOR,$notificacion->SYTN_NOMBRE,$notificacion->SYTN_CODIGO);

                $data_notificaciones_mixed[ $notificacion->NOTI_NOTIFICACION ] = $nueva_notificacion;
            }
            
        }

        // Ordenamos de notificación reciente a notificación antigua
        arsort($data_notificaciones_mixed);

        $data_notificaciones_mixed = array_values($data_notificaciones_mixed);

        return response()->json($data_notificaciones_mixed);
    }

    public function eliminarNotificacion( $request )
    {
        return NotificacionController::eliminarNotificacion( $request->id );
    }

}