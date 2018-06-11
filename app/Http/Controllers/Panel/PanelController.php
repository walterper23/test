<?php
namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerUsuarioRequest;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MUsuario;
use App\Model\MDenuncia;
use App\Model\MDocumento;
use App\Model\MDocumentoSemaforizado;
use App\Model\MSeguimiento;
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MEstadoDocumento;

class PanelController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this -> setLog('PanelController.log');
    }

    public function index(Request $request)
    {
        $view = $request -> get('view','all');

        $search = $request -> get('search');

        // Recuperar las direcciones asignadas al usuario
        $ids_direcciones = user() -> Direcciones -> pluck('DIRE_DIRECCION') -> toArray();

        // Recuperar los departamentos asignados al usuario
        $ids_departamentos = user() -> Departamentos -> pluck('DEPA_DEPARTAMENTO') -> toArray();

        /* 
        *  Recuperar los seguimientos que hayan pasado por las direcciones y departamentos anteriores
        *  Los seguimientos nos darán los documentos en los cuáles ha participado el usuario
        */
        $documentos = MSeguimiento::selectRaw('distinct(SEGU_DOCUMENTO) as id_documento')
                        -> whereIn('SEGU_DIRECCION_ORIGEN',$ids_direcciones)
                        -> orWhereIn('SEGU_DIRECCION_DESTINO',$ids_direcciones)
                        -> orWhereIn('SEGU_DEPARTAMENTO_ORIGEN',$ids_departamentos)
                        -> orWhereIn('SEGU_DEPARTAMENTO_DESTINO',$ids_departamentos)
                        -> pluck('id_documento')
                        -> toArray();

        // Recuperar el último seguimiento de cada documento
        $seguimientos = MSeguimiento::with('Documento','DireccionOrigen','DireccionDestino','DepartamentoOrigen','DepartamentoDestino','EstadoDocumento','Escaneos')
                        -> leftJoin('documentos','SEGU_DOCUMENTO','=','DOCU_DOCUMENTO')
                        -> leftJoin('system_tipos_documentos','SYTD_TIPO_DOCUMENTO','=','DOCU_SYSTEM_TIPO_DOCTO')
                        -> leftJoin('detalles','DETA_DETALLE','=','DOCU_DETALLE')
                        -> leftJoin('usuarios','USUA_USUARIO','=','SEGU_USUARIO')
                        -> leftJoin('usuarios_detalles','USDE_USUARIO_DETALLE','=','USUA_DETALLE')
                        -> leftJoin('system_estados_documentos','SYED_ESTADO_DOCUMENTO','=','DOCU_SYSTEM_ESTADO_DOCTO')
                        -> leftJoin('denuncias','DENU_DOCUMENTO','=','DOCU_DOCUMENTO')
                        -> where(function($query) use ($search) {
                            if (! is_null($search) && ! empty($search))
                            {
                                $search = "%$search%";
                                $query -> where('DOCU_NUMERO_DOCUMENTO','like',$search);
                                $query -> orWhere('DENU_NO_EXPEDIENTE','like',$search);
                                $query -> orWhere('DETA_DESCRIPCION','like',$search);
                                $query -> orWhere('SYTD_NOMBRE','like',$search);
                            }
                        })
                        -> where('DOCU_SYSTEM_ESTADO_DOCTO','!=',6) // Recepción eliminada
                        -> whereIn('SEGU_DOCUMENTO',$documentos)
                        -> whereRaw('SEGU_SEGUIMIENTO in (select max(SEGU_SEGUIMIENTO) from seguimiento group by SEGU_DOCUMENTO order by SEGU_SEGUIMIENTO desc)')
                        -> orderBy('SEGU_SEGUIMIENTO','DESC')
                        -> get();

        // Creamos un contenedor donde se almacenarán los seguimientos y documentos de acuerdo a ciertas clasificaciones
        $documentos = [
            'recientes'   => [], // Documentos/seguimientos que el usuario aún no ha leído
            'todos'       => [], // Todos los documentos encontrados
            'importantes' => [], // Los documentos que el usuario ha marcado como importantes
            'archivados'  => [], // Los documentos que el usuario ha marcado como archivados
            'rechazados'  => [], // Los documentos que fueron rechazados
            'finalizados' => [], // Los documentos que ya fueron finalizados
        ];

        // Recorremos todos los seguimientos encontrados y vamos guardando en el contenedor anterior
        foreach ($seguimientos as $seguimiento) {
            
            // Añadimos el seguimiento a Todos, si no es un documento archivado por el usuario
            if (! $seguimiento -> Documento -> archivado())
                $documentos['todos'][] = $seguimiento;
            
            // Si el usuario no ha leido el seguimiento, lo añadimos a Recientes
            if (! $seguimiento -> leido() )
            {
                $documentos['recientes'][] = $seguimiento;
            }

            // Si el usuario tiene marcado el documento como Importante, lo añadimos a Importantes
            if ($seguimiento -> Documento -> importante())
            {
                $documentos['importantes'][] = $seguimiento;
            }

            // Si el usuario tiene marcado el documento como Archivado, lo añadimos a Archivados
            if ($seguimiento -> Documento -> archivado())
            {
                $documentos['archivados'][] = $seguimiento;
            }

            // Si el documento fue rechazado
            if ($seguimiento -> Documento -> rechazado())
            {
                $documentos['rechazados'][] = $seguimiento;
            }

            // Si el documento fue finalizado
            if ($seguimiento -> Documento -> finalizado())
            {
                $documentos['finalizados'][] = $seguimiento;
            }

        }
        
        // Almacenar el título a mostrar de la vista y almacenar los documentos y seguimientos a mostrar de acuerdo al tipo de $view solicitado
        switch ($view) {
            case 'recents':
                $data['title']      = 'Documentos recientes';
                $data['documentos'] = $documentos['recientes'];
                $data['view']       = 'Recientes';
                break;
            case 'all':
                $data['title']      = 'Documentos recibidos';
                $data['documentos'] = $documentos['todos'];
                $data['view']       = 'Todos';
                break;
            case 'important':
                $data['title']      = 'Documentos importantes';
                $data['documentos'] = $documentos['importantes'];
                $data['view']       = 'Importantes';
                break;
            case 'archived':
                $data['title']      = 'Documentos archivados';
                $data['documentos'] = $documentos['archivados'];
                $data['view']       = 'Archivados';
                break;
            case 'rejected':
                $data['title']      = 'Documentos rechazados';
                $data['documentos'] = $documentos['rechazados'];
                $data['view']       = 'Rechazados';
                break;
            case 'finished':
                $data['title']      = 'Documentos finalizados';
                $data['documentos'] = $documentos['finalizados'];
                $data['view']       = 'Finalizados';
                break;
            default:
                $data['title']      = 'Documentos recibidos';
                $data['documentos'] = $documentos['todos'];
                $data['view']       = 'Todos';
                break;
        }

        $data['recientes']   = sizeof($documentos['recientes']);
        $data['todos']       = sizeof($documentos['todos']);
        $data['importantes'] = sizeof($documentos['importantes']);
        $data['archivados']  = sizeof($documentos['archivados']);
        $data['rechazados']  = sizeof($documentos['rechazados']);
        $data['finalizados'] = sizeof($documentos['finalizados']);

        $data['count']        = sizeof($data['documentos']);
        $data['field_search'] = sizeof($data['documentos']) ? '' : 'disabled';

        return view('Panel.Documentos.index') -> with($data);
    }

    public function manager(Request $request)
    {
        switch ($request -> action) {
            case 1: // Nuevo cambio de estado de documento
                $response = $this -> cambiarEstadoDocumento( $request );
                break;
            case 2: // Editar cambio de estado de documento
                $response = $this -> editarEstadoDocumento( $request );
                break;
            case 3: // Marcar documento como importante
                $response = $this -> marcarDocumentoImportante( $request );
                break;
            case 4: // Marcar documento como archivado
                $response = $this -> marcarDocumentoArchivado( $request );
                break;
            case 5: // Asignar número de expediente a denuncia
                $response = $this -> asignarNoExpedienteDenuncia( $request );
                break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }
        return $response;
    }

    // Formulario para realizar el cambio de estado de un documento
    public function formCambioEstadoDocumento(Request $request)
    {
        $documento = MDocumento::find( $request -> documento );

        // Recuperar las direcciones asignadas al usuario
        $ids_direcciones_origen = user() -> Direcciones() -> pluck('DIRE_DIRECCION') -> toArray();

        // Recuperar los departamentos asignados al usuario
        $ids_departamentos_origen = user() -> Departamentos() -> pluck('DEPA_DEPARTAMENTO') -> toArray();
        
        $data = [
            'title'         => 'Cambio de Estado de Documento',
            'url_send_form' => url('panel/documentos/manager'),
            'form_id'       => 'form-cambio-estado-documento',
            'action'        => 1,
            'documento'     => $documento -> getKey(),
        ];

        // Buscar el último seguimiento del documento
        $seguimiento = $documento -> Seguimientos() -> with('DireccionOrigen','DepartamentoOrigen','DireccionDestino','DepartamentoDestino') -> get() -> last();

        $data['direccion_origen']    = $seguimiento -> DireccionDestino -> getNombre();
        $data['departamento_origen'] = '';

        if( $seguimiento -> DepartamentoDestino )
            $data['departamento_origen'] = $seguimiento -> DepartamentoDestino -> getNombre();

        // Obtener todas las direcciones de destino existentes y disponibles con sus departamentos existentes y disponibles
        $direcciones = MDireccion::with('DepartamentosExistentesDisponibles')
                        -> select('DIRE_DIRECCION','DIRE_NOMBRE')
                        -> existenteDisponible()
                        -> orderBy('DIRE_NOMBRE')
                        -> get();

        $data['direcciones_destino'] = $direcciones -> pluck('DIRE_NOMBRE','DIRE_DIRECCION') -> toArray();
        
        // Obtener todos los departamentos de destino disponibles
        $data['departamentos_destino'] = [];

        foreach ($direcciones as $direccion)
        {
            foreach ($direccion -> DepartamentosExistentesDisponibles as $departamento)
            {
                $data['departamentos_destino'][] = [
                    $direccion -> getKey(),
                    $departamento -> getKey(),
                    $departamento -> getNombre()
                ];
            }
        }

        $data['system_estados_documentos'][1] = 'En seguimiento';

        if ( user() -> can('DOC.RECHAZAR') )
            $data['system_estados_documentos'][2] = 'Rechazar documento';

        if ( user() -> can('DOC.FINALIZAR') )
            $data['system_estados_documentos'][3] = 'Finalizar documento (resolver)';
    
        // Obtener los estados de documentos de sus direcciones y departamentos
        $estados = MEstadoDocumento::select('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO') -> existenteDisponible()
                    -> where(function($query) use ($seguimiento){
                        $query -> where(function($query) use($seguimiento){
                            $query -> where('ESDO_DIRECCION',$seguimiento -> DireccionDestino -> getKey());
                            $query -> whereNull('ESDO_DEPARTAMENTO');
                        });

                        if( $seguimiento -> DepartamentoDestino )
                            $query -> orWhere('ESDO_DEPARTAMENTO',$seguimiento -> DepartamentoDestino -> getKey());
                    })
                    -> pluck('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO')
                    -> toArray();

        $data['estados'] = $estados;

        // Verificamos si hay solicitud de contestación al documento
        $semaforo = MDocumentoSemaforizado::where('DOSE_DOCUMENTO',$seguimiento -> SEGU_DOCUMENTO)
                    -> whereIn('DOSE_ESTADO',[1,2]) // En espera, No atendido
                    -> whereNull('DOSE_SEGUIMIENTO_B')
                    -> get()
                    -> first();
        
        $data['contestar'] = $semaforo;

        return view('Panel.Documentos.formCambioEstadoDocumento') -> with($data);
    }

    // Método para cambiar el estado de un documento
    public function cambiarEstadoDocumento( $request )
    {
        $documento = MDocumento::find( $request -> documento );

        $ultimoSeguimiento = $documento -> Seguimientos -> last();

        if ($documento -> finalizado())
            return $this -> responseDangerJSON('<i class="fa fa-fw fa-warning"></i> El documento ya fue finalizado. No se permiten más cambios de estado.');

        if ($documento -> rechazado())
            return $this -> responseDangerJSON('<i class="fa fa-fw fa-warning"></i> El documento fue rechazado. No se permiten más cambios de estado.');
                
        switch ($request -> estado_documento) {
            case 2:
                $documento -> DOCU_SYSTEM_ESTADO_DOCTO = 5; // Documento rechazado
                $documento -> save();
                break;
            case 3:
                $documento -> DOCU_SYSTEM_ESTADO_DOCTO = 4; // Documento resuelto
                $documento -> save();
                break;
            default:
                if ($documento -> Seguimientos -> count() == 1) // Si el documento solo tenía un seguimiento o cambio de estado ...
                {
                    $documento -> DOCU_SYSTEM_ESTADO_DOCTO = 3; // ... lo cambiamos a Documento en seguimiento
                    $documento -> save();
                }
                break;
        }

        $departamento_destino = $request -> get('departamento_destino',0);
        if ($departamento_destino == 0)
            $departamento_destino = null;

        $seguimientoNuevo = new MSeguimiento;
        $seguimientoNuevo -> SEGU_USUARIO              = userKey();
        $seguimientoNuevo -> SEGU_DOCUMENTO            = $ultimoSeguimiento -> SEGU_DOCUMENTO;
        $seguimientoNuevo -> SEGU_DIRECCION_ORIGEN     = $ultimoSeguimiento -> SEGU_DIRECCION_DESTINO;
        $seguimientoNuevo -> SEGU_DEPARTAMENTO_ORIGEN  = $ultimoSeguimiento -> SEGU_DEPARTAMENTO_DESTINO;
        $seguimientoNuevo -> SEGU_DIRECCION_DESTINO    = $request -> direccion_destino;
        $seguimientoNuevo -> SEGU_DEPARTAMENTO_DESTINO = $departamento_destino;
        $seguimientoNuevo -> SEGU_ESTADO_DOCUMENTO     = $request -> estado;
        $seguimientoNuevo -> SEGU_OBSERVACION          = $request -> observacion;
        $seguimientoNuevo -> SEGU_INSTRUCCION          = $request -> instruccion;
        $seguimientoNuevo -> save();

        if ($request -> get('contestar') == 1)
        {
            $documentoSemaforizado = MDocumentoSemaforizado::where('DOSE_DOCUMENTO',$documento -> getKey())
                    -> whereIn('DOSE_ESTADO',[1,2]) // En espera, No atendido
                    -> whereNull('DOSE_SEGUIMIENTO_B')
                    -> get() -> first();

            $documentoSemaforizado -> DOSE_ESTADO              = 3; // Respondido
            $documentoSemaforizado -> DOSE_SEGUIMIENTO_B       = $seguimientoNuevo -> getKey();
            $documentoSemaforizado -> DOSE_RESPUESTA           = $request -> contestacion;
            $documentoSemaforizado -> DOSE_RESPUESTA_FECHA     = \Carbon\Carbon::now();
            $documentoSemaforizado -> save();
        }
        
        if($request -> estado_documento == 1 && $request -> get('semaforizar') == 1) // Permitir semaforizar el documento, si aun estará en seguimiento
        {
            // Calculamos la fecha límite para responder a la solicitud de contestación
            $fecha_limite = \Carbon\Carbon::now() -> addDays( config_var('Sistema.Dias.Limite.Semaforo') ) -> format('Y-m-d');

            $semaforo = new MDocumentoSemaforizado;
            $semaforo -> DOSE_DOCUMENTO     = $documento -> getKey();
            $semaforo -> DOSE_USUARIO       = userKey();
            $semaforo -> DOSE_ESTADO        = 1; // En espera de contestación
            $semaforo -> DOSE_SOLICITUD     = $request -> instruccion;
            $semaforo -> DOSE_FECHA_LIMITE  = $fecha_limite;
            $semaforo -> DOSE_SEGUIMIENTO_A = $seguimientoNuevo -> getKey();
            $semaforo -> save();
        }

        $message = sprintf('<i class="fa fa-fw fa-flash"></i> Seguimiento <b>#%s</b> creado',$seguimientoNuevo -> getCodigo());

        return $this -> responseSuccessJSON($message);
    }

    public function formEditarCambioEstadoDocumento(Request $request)
    {

    }

    public function editarCambioEstadoDocumento( $request )
    {

    }

    // Método para marcar como Importante un documento para el usuario
    public function marcarDocumentoImportante( $request )
    {
        try {
            $documento = MDocumento::find( $request ->  documento );
            $documento -> marcarImportante();
            $documento -> save();

            $importante = $documento -> importante();

            $message = sprintf('Documento <b>#%s</b> importante <i class="fa fa-fw fa-star"></i>', $documento -> getCodigo());

            return $this -> responseWarningJSON(['message'=>$message,'importante'=>$importante]);

        } catch(Exception $error) {

        }

    }

    // Método para marcar como Archivado un documento para el usuario
    public function marcarDocumentoArchivado( $request )
    {
        try {
            $documento = MDocumento::find( $request ->  documento );
            $documento -> marcarArchivado();
            $documento -> save();

            if( $documento -> archivado() ){
                $message = sprintf('Documento <b>#%s</b> archivado <i class="fa fa-fw fa-archive"></i>', $documento -> getCodigo());
                return $this -> responseInfoJSON(['message'=>$message,'archivado'=>true]);
            }
            
            return $this -> responseTypeJSON(['archivado'=>false]);
        } catch(Exception $error) {

        }

    }

    public function formAsignarNoExpedienteDenuncia(Request $request)
    {
        if (user() -> cant('DOC.CREAR.NO.EXPE'))
            abort(403);

        $data = [
            'title'         => 'Nó. de expediende de denuncia',
            'url_send_form' => url('panel/documentos/manager'),
            'form_id'       => 'form-no-expediente-denuncia',
            'action'        => 5,
            'id'            => $request -> id,
        ];

        $denuncia = MDenuncia::where('DENU_DOCUMENTO',$request -> id) -> first();

        if (is_null($denuncia))
        {
            abort(404);
        }

        $data['no_expediente'] = $denuncia -> getNoExpediente();

        return view('Panel.Documentos.formAsignarNoExpedienteDenuncia') -> with($data);
    }

    public function asignarNoExpedienteDenuncia( $request )
    {
        if (user() -> cant('DOC.CREAR.NO.EXPE'))
            abort(403);

        $documento = MDocumento::with('Denuncia') -> find( $request -> id );

        $documento -> Denuncia -> DENU_NO_EXPEDIENTE = $request -> expediente;
        $documento -> Denuncia -> save();

        $message = sprintf('Nó. expediente <b>%s</b> asignado a Documento <b>#%s</b>',$documento -> Denuncia -> getNoExpediente(), $documento -> getCodigo());

        return $this -> responseSuccessJSON($message);
    }

}