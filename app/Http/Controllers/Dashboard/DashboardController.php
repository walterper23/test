<?php
namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Carbon\Carbon;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MDocumento;
use App\Model\MDocumentoForaneo;
use App\Model\MNotificacion;
use App\Model\System\MSystemTipoDocumento;

class DashboardController extends BaseController
{
    protected $documentosRepository;

    public function __construct(){
        $this -> setLog('DashboardController.log');
    }

    public function index(Request $request){

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

    public function reporteDocumentos($request)
    {
        $anio_actual = Carbon::now() -> year;

        // Buscar todos los documentos existentes. No elimininados
        $documentos_locales = MDocumento::
                     leftJoin('system_tipos_documentos','SYTD_TIPO_DOCUMENTO','=','DOCU_SYSTEM_TIPO_DOCTO')
                    -> leftJoin('system_estados_documentos','SYED_ESTADO_DOCUMENTO','=','DOCU_SYSTEM_ESTADO_DOCTO')
                    -> leftJoin('detalles','DETA_DETALLE','=','DOCU_DETALLE')
                    -> existente()
                    -> whereYear('DETA_FECHA_RECEPCION',$anio_actual)
                    -> where('DOCU_TIPO_RECEPCION',1) // Captura local
                    -> get();

        $documentos_foraneos = MDocumentoForaneo::
                     leftJoin('system_tipos_documentos','SYTD_TIPO_DOCUMENTO','=','DOFO_SYSTEM_TIPO_DOCTO')
                    -> leftJoin('detalles','DETA_DETALLE','=','DOFO_DETALLE')
                    -> existente()
                    -> whereYear('DETA_FECHA_RECEPCION',$anio_actual)
                    -> get();

        $data = [
            'hoy' => $this -> documentosHoy( $documentos_locales, $documentos_foraneos ),
            'semana' => $this -> documentosSemana( $documentos_locales, $documentos_foraneos ),
            'mes' => $this -> documentosMes( $documentos_locales, $documentos_foraneos ),
            'anual' => $this -> documentosAnual( $documentos_locales, $documentos_foraneos )
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
            $conteos['denuncias'][ $dia ] = 0;
            $conteos['doctos_denuncias'][ $dia ] = 0;
            $conteos['documentos'][ $dia ] = 0;
        }

        $inicio_semana = Carbon::now() -> startOfWeek() -> format('Y-m-d');

        $documentos_locales = $documentos_locales -> filter(function($docto) use($inicio_semana, $fin_semana){
            return ($docto -> DETA_FECHA_RECEPCION >= $inicio_semana && $docto -> DETA_FECHA_RECEPCION <= $fin_semana);
        }); // Sólo los documentos del mes actual
        
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
        }); // Sólo los documentos del mes actual

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
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0
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
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0
        ];

        foreach ($documentos_locales as $doc) {
            $data[ $doc -> getEstadoDocumento() ]++;
        }

        $data[2] += $documentos_foraneos -> count(); // Le sumamos todos los documentos foráneos como recepcionados

        $data = array_values($data);

        return $data;
    }

}
