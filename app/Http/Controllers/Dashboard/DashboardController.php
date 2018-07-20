<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MDocumento;
use App\Model\MDocumentoForaneo;
use App\Model\MNotificacion;
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
        $this -> setLog('DashboardController.log');
    }

    public function index(Request $request)
    {
        $data_notificaciones['recepcion_local']   = [];
        $data_notificaciones['recepcion_foranea'] = [];
        $data_notificaciones['panel_trabajo']     = [];
        $data_notificaciones['semaforizacion']    = [];

        $permisos_usuario = user() -> Permisos -> pluck('SYPE_PERMISO') -> toArray();

        $ids_direcciones_usuario = user() -> Direcciones -> pluck('DIRE_DIRECCION') -> toArray();
        $ids_departamentos_usuario = user() -> Departamentos -> pluck('DEPA_DEPARTAMENTO') -> toArray();

        $sql_notificaciones_recepcion =
        'SELECT * FROM usuarios
            JOIN notificaciones ON !JSON_CONTAINS(NOTI_USUARIOS_VISTO, CAST(USUA_USUARIO AS JSON),"$")
            WHERE NOTI_SYSTEM_TIPO IN (1,2,4)
            AND NOTI_DELETED = 0
            AND USUA_DELETED = 0
            AND USUA_USUARIO = ?
            AND NOTI_SYSTEM_PERMISO IN (?)
            ORDER BY NOTI_SYSTEM_TIPO ASC, NOTI_CREATED_AT DESC
        ';

        $notificaciones_recepcion = DB::select($sql_notificaciones_recepcion,[userKey(),implode(',',$permisos_usuario)]);

        $notificaciones_areas = [];
        if ( sizeof($ids_direcciones_usuario) > 0 )
        {
            $ids_direcciones_usuario = implode(',', $ids_direcciones_usuario);
            $ids_departamentos_usuario[] = 0;
            $ids_departamentos_usuario = implode(',', $ids_departamentos_usuario);
            $sql_notificaciones_areas =
            'SELECT * FROM usuarios
                JOIN notificaciones ON !JSON_CONTAINS(NOTI_USUARIOS_VISTO, CAST(USUA_USUARIO AS JSON),"$")
                JOIN notificaciones_areas ON NOTI_NOTIFICACION = NOAR_NOTIFICACION
                WHERE NOTI_SYSTEM_TIPO = 3
                AND NOTI_DELETED = 0
                AND NOAR_ENABLED = 1
                AND (NOAR_DIRECCION IN (?) OR NOAR_DEPARTAMENTO IN (?) )
                AND USUA_DELETED = 0
                AND USUA_USUARIO = ?
                ORDER BY NOTI_SYSTEM_TIPO ASC, NOTI_CREATED_AT DESC
                    ';

            $notificaciones_areas = DB::select($sql_notificaciones_areas,[$ids_direcciones_usuario, $ids_departamentos_usuario,userKey()]);
        }

        $grupos_notificaciones_recepcion = [];
        foreach ($notificaciones_recepcion as $notificacion) {
            $grupos_notificaciones_recepcion[ $notificacion -> NOTI_SYSTEM_TIPO ][] = $notificacion;
        }

        $grupos_notificaciones_areas = [];
        foreach ($notificaciones_areas as $notificacion) {
            $grupos_notificaciones_areas[ $notificacion -> NOTI_SYSTEM_TIPO ][] = $notificacion;
        }

        if( array_key_exists(1, $grupos_notificaciones_recepcion) )
        {
            $data_notificaciones['recepcion_local'] = $grupos_notificaciones_recepcion[1];
        }

        if( array_key_exists(2, $grupos_notificaciones_recepcion) )
        {
            $data_notificaciones['recepcion_foranea'] = $grupos_notificaciones_recepcion[2];
        }

        if( array_key_exists(3, $grupos_notificaciones_areas) )
        {
            $data_notificaciones['panel_trabajo'] = $grupos_notificaciones_areas[3];
        }

        if( array_key_exists(4, $grupos_notificaciones_recepcion) )
        {
            $data_notificaciones['semaforizacion'] = $grupos_notificaciones_recepcion[4];
        }

        $fecha_documentos_recibidos_hoy = Carbon::now() -> format('Y-m-d');

        $inicio_semana           = Carbon::now() -> startOfWeek();
        $fin_semana              = Carbon::now() -> endOfWeek();
        $fecha_documentos_semana = sprintf('Lun %s - Dom %s', $inicio_semana -> format('d'), $fin_semana -> format('d') );

        return view('Dashboard.indexDashboard', compact('fecha_documentos_recibidos_hoy','fecha_documentos_semana')) -> with($data_notificaciones);
    }


    public function manager(Request $request)
    {
        switch ($request -> action) {
            case 'reporte-documentos' :
                $response = $this -> reporteDocumentos( $request );
                break;
            case 'eliminar-notificacion' : 
                $response = $this -> eliminarNotificacion( $request );
                break;
            default:
                abort(404);
                break;
        }

        return $response;
    }

    public function reporteDocumentos( $request )
    {
        $anio_actual = Carbon::now() -> year;

        // Buscar todos los documentos existentes. No elimininados
        $documentos_locales = MDocumento::
                     leftJoin('system_tipos_documentos','SYTD_TIPO_DOCUMENTO','=','DOCU_SYSTEM_TIPO_DOCTO')
                    //-> leftJoin('system_estados_documentos','SYED_ESTADO_DOCUMENTO','=','DOCU_SYSTEM_ESTADO_DOCTO')
                    -> leftJoin('detalles','DETA_DETALLE','=','DOCU_DETALLE')
                    //-> existente()
                    -> whereYear('DETA_FECHA_RECEPCION',$anio_actual)
                    -> where('DOCU_TIPO_RECEPCION',1) // Captura local
                    -> get();

        $documentos_foraneos = MDocumentoForaneo::
                     leftJoin('system_tipos_documentos','SYTD_TIPO_DOCUMENTO','=','DOFO_SYSTEM_TIPO_DOCTO')
                    -> leftJoin('detalles','DETA_DETALLE','=','DOFO_DETALLE')
                    //-> existente()
                    -> whereYear('DETA_FECHA_RECEPCION',$anio_actual)
                    -> get();

        $data = [
            'hoy'    => $this -> documentosHoy( $documentos_locales, $documentos_foraneos ),
            'semana' => $this -> documentosSemana( $documentos_locales, $documentos_foraneos ),
            'mes'    => $this -> documentosMes( $documentos_locales, $documentos_foraneos ),
            'anual'  => $this -> documentosAnual( $documentos_locales, $documentos_foraneos )
        ];

        return response() -> json($data);
    }

    public function documentosHoy( $documentos_locales, $documentos_foraneos )
    {
        $locales  = [];
        $foraneos = [];

        $tipos_documentos = MSystemTipoDocumento::orderBy('SYTD_TIPO_DOCUMENTO') -> get(); // Recuperamos todos los tipos de documentos existentes
        // Recorremos todos los tipos de documentos existentes para iniciar los contadores en 0
        $tipos_documentos -> map(function($tipo) use(&$locales, &$foraneos){
            $locales[ $tipo -> getKey()  ] = 0;
            $foraneos[ $tipo -> getKey()  ] = 0;
        });

        $hoy = Carbon::now() -> format('Y-m-d');
        $documentos_locales = $documentos_locales -> where('DETA_FECHA_RECEPCION',$hoy); // Sólo los documentos de hoy
        $documentos_foraneos = $documentos_foraneos -> where('DETA_FECHA_RECEPCION',$hoy); // Sólo los documentos de hoy
        
        // Recorremos todos los documentos locales para guardar el conteo
        foreach ($documentos_locales as $docto) {
            $locales[ $docto -> getTipoDocumento() ]++;
        }

        // Recorremos todos los documentos foraneos para guardar el conteo
        foreach ($documentos_foraneos as $docto) {
            $foraneos[ $docto -> getTipoDocumento() ]++;
        }

        ksort($locales); // Ordenamos los índices ascendentemente
        ksort($foraneos); // Ordenamos los índices ascendentemente

        $data = [
            'labels'   => $tipos_documentos -> pluck('SYTD_NOMBRE') -> toArray(), // Denuncia, Ficha, Circular, Documento denuncia, Invitación...
            'locales'  => array_values($locales), // [1, 2, 3, 4]
            'foraneos' => array_values($foraneos), // [1, 2, 3, 4]
        ];

        return $data;
    }

    public function documentosSemana( $documentos_locales, $documentos_foraneos )
    {
        $inicio_semana = Carbon::now() -> startOfWeek();
        $fin_semana    = Carbon::now() -> endOfWeek() -> format('Y-m-d');

        $dias = [
            $inicio_semana -> format('d'),
            $inicio_semana -> addDay() -> format('d'),
            $inicio_semana -> addDay() -> format('d'),
            $inicio_semana -> addDay() -> format('d'),
            $inicio_semana -> addDay() -> format('d'),
            $inicio_semana -> addDay() -> format('d'),
            $inicio_semana -> addDay() -> format('d'),
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

        $inicio_semana = Carbon::now() -> startOfWeek() -> format('Y-m-d');

        $documentos_locales = $documentos_locales -> filter(function($docto) use($inicio_semana, $fin_semana){
            return ($docto -> DETA_FECHA_RECEPCION >= $inicio_semana && $docto -> DETA_FECHA_RECEPCION <= $fin_semana);
        }); // Sólo los documentos de la semana actual
        
        foreach ($documentos_locales as $docto) {
            $dia_documento = substr($docto -> DETA_FECHA_RECEPCION,-2); // capturamos el día de recepción

            if( $docto -> getTipoDocumento() == 1 ) // Denuncia
                $conteos['denuncias'][ $dia_documento ]++;
            else if( $docto -> getTipoDocumento() == 2 ) // Documento para denuncia
                $conteos['doctos_denuncias'][ $dia_documento ]++;
            else // Otro tipo de documento
                $conteos['documentos'][ $dia_documento ]++;
        }

        $documentos_foraneos = $documentos_foraneos -> filter(function($docto) use($inicio_semana, $fin_semana){
            return ($docto -> DETA_FECHA_RECEPCION >= $inicio_semana && $docto -> DETA_FECHA_RECEPCION <= $fin_semana);
        }); // Sólo los documentos de la semana actual

        foreach ($documentos_foraneos as $docto) {
            $dia_documento = substr($docto -> DETA_FECHA_RECEPCION,-2); // capturamos el día de recepción

            if( $docto -> getTipoDocumento() == 1 ) // Denuncia
                $conteos['denuncias'][ $dia_documento ]++;
            else if( $docto -> getTipoDocumento() == 2 ) // Documento para denuncia
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
        $inicio_mes = $hoy -> startOfMonth() -> format('Y-m-d');
        $fin_mes    = $hoy -> endOfMonth() -> format('Y-m-d');

        $documentos_locales = $documentos_locales -> filter(function($docto) use($inicio_mes, $fin_mes){
            return ($docto -> DETA_FECHA_RECEPCION >= $inicio_mes && $docto -> DETA_FECHA_RECEPCION <= $fin_mes);
        }); // Sólo los documentos locales del mes actual

        $documentos_foraneos = $documentos_foraneos -> filter(function($docto) use($inicio_mes, $fin_mes){
            return ($docto -> DETA_FECHA_RECEPCION >= $inicio_mes && $docto -> DETA_FECHA_RECEPCION <= $fin_mes);
        }); // Sólo los documentos foraneos del mes actual
        
        $data = [
            2 => 0, // En recepción
            3 => 0, // En seguimiento
            4 => 0, // Resueltos
            5 => 0, // Rechazados
            6 => 0, // Eliminados
        ];

        foreach ($documentos_locales as $doc) {
            $data[ $doc -> getEstadoDocumento() ]++;
        }

        $data[2] += $documentos_foraneos -> count(); // Le sumamos todos los documentos foráneos como recepcionados

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

        foreach ($documentos_locales as $doc) {
            $data[ $doc -> getEstadoDocumento() ]++;
        }

        $data[2] += $documentos_foraneos -> count(); // Le sumamos todos los documentos foráneos como recepcionados

        $data = array_values($data);

        return $data;
    }

    public function eliminarNotificacion( $request )
    {
        return NotificacionController::eliminarNotificacion( $request -> id );
    }

}