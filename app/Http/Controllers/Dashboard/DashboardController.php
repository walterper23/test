<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MDocumento;
use App\Model\MUsuario;
use App\Model\System\MSystemTipoDocumento;

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
            case 'reporte-documentos' :
                $response = $this->reporteDocumentos( $request );
                break;
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

    public function reporteDocumentos( $request )
    {
        $anio_actual = Carbon::now()->year;

        // Buscar todos los documentos existentes. No eliminados
        $documentos = MDocumento::with('TipoDocumento','Detalle')
            ->join('detalles',function($query) use ($anio_actual){
                $query->on('DOCU_DETALLE','=','DETA_DETALLE');
                $query->where('DETA_ANIO',$anio_actual);
            })->get();

        $documentos_locales = $documentos_foraneos = collect();

        foreach ($documentos as $documento) {
            if( $documento->isLocal() )
            {
                $documentos_locales[] = $documento;
            }
            else
            {
                $documentos_foraneos[] = $documento;
            }
        }

        $data = [
            'hoy'    => $this->documentosHoy( $documentos_locales, $documentos_foraneos ),
            'semana' => $this->documentosSemana( $documentos_locales, $documentos_foraneos ),
            'mes'    => $this->documentosMes( $documentos_locales, $documentos_foraneos ),
            'anual'  => $this->documentosAnual( $documentos_locales, $documentos_foraneos )
        ];

        return response()->json($data);
    }

    public function documentosHoy( $documentos_locales, $documentos_foraneos )
    {
        $locales  = [];
        $foraneos = [];

        $tipos_documentos = MSystemTipoDocumento::orderBy('SYTD_TIPO_DOCUMENTO')->get(); // Recuperamos todos los tipos de documentos existentes
        // Recorremos todos los tipos de documentos existentes para iniciar los contadores en 0
        foreach ($tipos_documentos as $tipo) {
            $locales[ $tipo->getKey() ] = 0;
            $foraneos[ $tipo->getKey() ] = 0;
        };

        $hoy = Carbon::now()->format('Y-m-d');
        $documentos_locales = $documentos_locales->where('DETA_FECHA_RECEPCION',$hoy); // Sólo los documentos de hoy
        $documentos_foraneos = $documentos_foraneos->where('DETA_FECHA_RECEPCION',$hoy); // Sólo los documentos de hoy
        
        // Recorremos todos los documentos locales para guardar el conteo
        foreach ($documentos_locales as $docto) {
            $locales[ $docto->getTipoDocumento() ]++;
        }

        // Recorremos todos los documentos foraneos para guardar el conteo
        foreach ($documentos_foraneos as $docto) {
            $foraneos[ $docto->getTipoDocumento() ]++;
        }

        ksort($locales); // Ordenamos los índices ascendentemente
        ksort($foraneos); // Ordenamos los índices ascendentemente

        $data = [
            'labels'   => $tipos_documentos->pluck('SYTD_NOMBRE')->toArray(), // Denuncia, Ficha, Circular, Documento denuncia, Invitación...
            'locales'  => array_values($locales), // [1, 2, 3, 4]
            'foraneos' => array_values($foraneos), // [1, 2, 3, 4]
        ];

        return $data;
    }

    public function documentosSemana( $documentos_locales, $documentos_foraneos )
    {
        $inicio_semana = Carbon::now()->startOfWeek();
        $fin_semana    = Carbon::now()->endOfWeek()->format('Y-m-d');

        $dias = [
            $inicio_semana->format('d'),
            $inicio_semana->addDay()->format('d'),
            $inicio_semana->addDay()->format('d'),
            $inicio_semana->addDay()->format('d'),
            $inicio_semana->addDay()->format('d'),
            $inicio_semana->addDay()->format('d'),
            $inicio_semana->addDay()->format('d'),
        ];

        $data['labels'][] = 'Lun ' . $dias[0];
        $data['labels'][] = 'Mar ' . $dias[1];
        $data['labels'][] = 'Mie ' . $dias[2];
        $data['labels'][] = 'Jue ' . $dias[3];
        $data['labels'][] = 'Vie ' . $dias[4];
        $data['labels'][] = 'Sáb ' . $dias[5];
        $data['labels'][] = 'Dom ' . $dias[6];

        foreach ($dias as $dia) {
            $conteos['denuncias'][ $dia ]        = 0;
            $conteos['doctos_denuncias'][ $dia ] = 0;
            $conteos['documentos'][ $dia ]       = 0;
        }

        $inicio_semana = Carbon::now()->startOfWeek()->format('Y-m-d');

        $documentos_locales = $documentos_locales->filter(function($docto) use($inicio_semana, $fin_semana){
            return ($docto->Detalle->getFechaRecepcion() >= $inicio_semana && $docto->Detalle->getFechaRecepcion() <= $fin_semana);
        }); // Sólo los documentos de la semana actual
        
        foreach ($documentos_locales as $docto) {
            $dia_documento = substr($docto->Detalle->getFechaRecepcion(),-2); // capturamos el día de recepción

            if( $docto->getTipoDocumento() == 1 ) // Denuncia
                $conteos['denuncias'][ $dia_documento ]++;
            else if( $docto->getTipoDocumento() == 2 ) // Documento para denuncia
                $conteos['doctos_denuncias'][ $dia_documento ]++;
            else // Otro tipo de documento
                $conteos['documentos'][ $dia_documento ]++;
        }

        $documentos_foraneos = $documentos_foraneos->filter(function($docto) use($inicio_semana, $fin_semana){
            return ($docto->Detalle->getFechaRecepcion() >= $inicio_semana && $docto->Detalle->getFechaRecepcion() <= $fin_semana);
        }); // Sólo los documentos de la semana actual

        foreach ($documentos_foraneos as $docto) {
            $dia_documento = substr($docto->Detalle->getFechaRecepcion(),-2); // capturamos el día de recepción

            if( $docto->getTipoDocumento() == 1 ) // Denuncia
                $conteos['denuncias'][ $dia_documento ]++;
            else if( $docto->getTipoDocumento() == 2 ) // Documento para denuncia
                $conteos['doctos_denuncias'][ $dia_documento ]++;
            else // Otro tipo de documento
                $conteos['documentos'][ $dia_documento ]++;
        }

        foreach( $conteos as $categoria => $value ){
            foreach ($dias as $dia) {
                $data[ $categoria ][] = $conteos[ $categoria ][ $dia ];
            }
        }

        return $data;
    }

    public function documentosMes( $documentos_locales, $documentos_foraneos )
    {
        $hoy        = Carbon::now();
        $inicio_mes = $hoy->startOfMonth()->format('Y-m-d');
        $fin_mes    = $hoy->endOfMonth()->format('Y-m-d');

        $documentos_locales = $documentos_locales->filter(function($docto) use($inicio_mes, $fin_mes){
            return ($docto->Detalle->getFechaRecepcion() >= $inicio_mes && $docto->Detalle->getFechaRecepcion() <= $fin_mes);
        }); // Sólo los documentos locales del mes actual

        $documentos_foraneos = $documentos_foraneos->filter(function($docto) use($inicio_mes, $fin_mes){
            return ($docto->Detalle->getFechaRecepcion() >= $inicio_mes && $docto->Detalle->getFechaRecepcion() <= $fin_mes);
        }); // Sólo los documentos foraneos del mes actual
        
        $data = [
            2 => 0, // En recepción
            3 => 0, // En seguimiento
            4 => 0, // Resueltos
            5 => 0, // Rechazados
            6 => 0, // Eliminados
        ];

        foreach ($documentos_locales as $documento) {
            $data[ $documento->getEstadoDocumento() ]++;
        }

        $data = array_values($data);

        return $data;
    }

    public function documentosAnual( $documentos_locales, $documentos_foraneos )
    {
        $data = [
            2 => 0, // En recepción
            3 => 0, // En seguimiento
            4 => 0, // Resueltos
            5 => 0, // Rechazados
            6 => 0, // Eliminados
        ];

        foreach ($documentos_locales as $documento) {
            $data[ $documento->getEstadoDocumento() ]++;
        }

        $data = array_values($data);

        return $data;
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
        $ids_direcciones_usuario = user()->Direcciones->pluck('DIRE_DIRECCION')->toArray();
        $ids_departamentos_usuario = user()->Departamentos->pluck('DEPA_DEPARTAMENTO')->toArray();
        
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