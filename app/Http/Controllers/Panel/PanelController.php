<?php
namespace App\Http\Controllers\Panel;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Dashboard\NotificacionController;

/* Models */
use App\Model\MDenuncia;
use App\Model\MDocumento;
use App\Model\MDocumentoSemaforizado;
use App\Model\MSeguimiento;
use App\Model\MSeguimientoDispersion;
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MEstadoDocumento;

use App\Panel\PaginadorDocumentos;

class PanelController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('can:DOC.CREAR.NO.EXPE',['only'=>['formAsignarNoExpedienteDenuncia','asignarNoExpedienteDenuncia']]);

        $this->setLog('PanelController.log');
    }

    public function index(Request $request)
    {
    	// Preparamos las variables para la visualización del listado de documentos
        $view         = $request->get('view','all'); // Clasificación de documentos para ver
        $search_folio = $request->get('search_folio'); // Filtro de búsqueda por folio
        $search_anio  = date('Y');
        $search       = $request->get('search'); // Filtro de búsqueda realizado
        $intervalo    = $request->get('step',10); // Cantidad de documentos por página
        $pagina       = $request->get('page',1); // Número de página

        $data['search_folio'] = $search_folio;

        // Recuperar las direcciones asignadas al usuario
        $ids_direcciones = session('DireccionesKeys');

        // Recuperar los departamentos asignados al usuario
        $ids_departamentos = session('DepartamentosKeys');

        // Recuperar el último seguimiento de cada documento
        $seguimientos = MSeguimiento::leftJoin('documentos','SEGU_DOCUMENTO','=','DOCU_DOCUMENTO')
                        ->leftJoin('system_tipos_documentos','SYTD_TIPO_DOCUMENTO','=','DOCU_SYSTEM_TIPO_DOCTO')
                        ->leftJoin('detalles','DETA_DETALLE','=','DOCU_DETALLE')
                        ->leftJoin('usuarios','USUA_USUARIO','=','SEGU_USUARIO')
                        ->leftJoin('usuarios_detalles','USDE_USUARIO_DETALLE','=','USUA_DETALLE')
                        ->leftJoin('system_estados_documentos','SYED_ESTADO_DOCUMENTO','=','DOCU_SYSTEM_ESTADO_DOCTO')
                        ->leftJoin('denuncias','DENU_DOCUMENTO','=','DOCU_DOCUMENTO')
                        ->whereIn('SEGU_DOCUMENTO',function($query) use ($ids_direcciones,$ids_departamentos){
                            /* 
                            *  Recuperar los seguimientos que hayan pasado por las direcciones y departamentos anteriores
                            *  Los seguimientos nos darán los documentos en los cuáles ha participado el usuario
                            */
                            $query->selectRaw('distinct(SEGU_DOCUMENTO) as id_documento')
                                ->from('seguimiento')
                                ->leftJoin('seguimiento_dispersion','SEGU_SEGUIMIENTO','=','SEDI_SEGUIMIENTO')
                                ->whereIn('SEGU_DIRECCION_ORIGEN',$ids_direcciones)
                                ->orWhereIn('SEGU_DIRECCION_DESTINO',$ids_direcciones)
                                ->orWhereIn('SEGU_DEPARTAMENTO_ORIGEN',$ids_departamentos)
                                ->orWhereIn('SEGU_DEPARTAMENTO_DESTINO',$ids_departamentos)
                                ->orWhereIn('SEDI_DIRECCION',$ids_direcciones) // Seguimiento disperso
                                ->orWhereIn('SEDI_DEPARTAMENTO',$ids_departamentos) // Seguimiento disperso
                                ;
                        }) // Los documentos que solo puede ver el usuario
                        ->where('DOCU_SYSTEM_ESTADO_DOCTO','!=',6) // Recepción no eliminada
                        ->whereRaw('SEGU_SEGUIMIENTO in (select max(SEGU_SEGUIMIENTO) as id_max from seguimiento group by SEGU_DOCUMENTO order by id_max desc)')
                        ;

        if ($search_folio) {

            $view = 'all';
            
            if (strpos($search_folio,'-') !== false) {
                list($search_anio,$search_folio) = explode('-', $search_folio);
            }

            $seguimientos->where('DOCU_FOLIO',$search_folio);
        }
        
        $seguimientos->where('DETA_ANIO',$search_anio);

        if ($search) {
            $seguimientos->where(function($query) use ($search){
                $search = "%$search%";
                $query->orWhere('DOCU_NUMERO_DOCUMENTO','like',$search);
                $query->orWhere('DENU_NO_EXPEDIENTE','like',$search);
                $query->orWhere('DETA_DESCRIPCION','like',$search);
                $query->orWhere('SYTD_NOMBRE','like',$search);
            });

            $view = 'all';
        }
        
        switch ($view) {
            case 'pending':

                $seguimientos->where(function($query) use ($ids_direcciones,$ids_departamentos){
                    $query->whereIn('SEGU_DIRECCION_DESTINO',$ids_direcciones);
                    $query->orWhereIn('SEGU_DEPARTAMENTO_DESTINO',$ids_departamentos);
                });

                $seguimientos->whereRaw('!JSON_CONTAINS(DOCU_ARCHIVADO, CAST('.userKey().' AS JSON),"$")');
                // $seguimientos->whereNotIn('DOCU_SYSTEM_ESTADO_DOCTO',[1,2]);

                $data['title'] = 'Documentos por turnar';
                $data['view']  = 'Por turnar';
                $parametros    = ['view'=>'pending'];
                break;
            case 'moved':

                $seguimientos->where(function($query) use ($ids_direcciones,$ids_departamentos){
                    $query->whereIn('SEGU_DIRECCION_ORIGEN',$ids_direcciones);
                    $query->orWhereIn('SEGU_DEPARTAMENTO_ORIGEN',$ids_departamentos);
                });

                $seguimientos->whereRaw('!JSON_CONTAINS(DOCU_ARCHIVADO, CAST('.userKey().' AS JSON),"$")');
                $seguimientos->where('DOCU_SYSTEM_ESTADO_DOCTO',3); // Documento en seguimiento

                $data['title'] = 'Documentos turnados';
                $data['view']  = 'Turnados';
                $parametros    = ['view'=>'moved'];
                break;
            case 'important':

                $seguimientos->whereRaw('JSON_CONTAINS(DOCU_IMPORTANTE, CAST('.userKey().' AS JSON),"$")');

                $data['title'] = 'Documentos importantes';
                $data['view']  = 'Importantes';
                $parametros    = ['view'=>'important'];
                break;
            case 'archived':

                $seguimientos->whereRaw('JSON_CONTAINS(DOCU_ARCHIVADO, CAST('.userKey().' AS JSON),"$")');

                $data['title'] = 'Documentos archivados';
                $data['view']  = 'Archivados';
                $parametros    = ['view'=>'archived'];
                break;
            case 'rejected':

                $seguimientos->where('DOCU_SYSTEM_ESTADO_DOCTO',5); // Documento rechazado

                $data['title'] = 'Documentos rechazados';
                $data['view']  = 'Rechazados';
                $parametros    = ['view'=>'rejected'];
                break;
            case 'finished':

                $seguimientos->where('DOCU_SYSTEM_ESTADO_DOCTO',4); // Documento finalizado

                $data['title'] = 'Documentos finalizados';
                $data['view']  = 'Finalizados';
                $parametros    = ['view'=>'finished'];
                break;
            default:
                $data['title'] = 'Todos los documentos';
                $data['view']  = 'Todos';
                $parametros    = ['view'=>'all'];
                break;
        }

        $seguimientos = $seguimientos->orderBy('SEGU_SEGUIMIENTO','DESC')->get(); // Listar desde el seguimiento más reciente

        $data['search'] = $search;

        $paginador = new PaginadorDocumentos( $seguimientos, $intervalo, $pagina );
        $paginador->addParametros($parametros + compact('search_folio','search'));

        $data['paginador'] = $paginador;

        // Obtener todas las direcciones de destino existentes y disponibles con sus departamentos existentes y disponibles
        $direcciones = MDireccion::with('DepartamentosExistentesDisponibles')
                       ->select('DIRE_DIRECCION','DIRE_NOMBRE')
                       ->siExistenteDisponible()
                       ->orderBy('DIRE_NOMBRE')
                       ->get();

        $data['direcciones'] = $direcciones;

        return view('Panel.Documentos.index')->with($data);
    }

    public function manager(Request $request)
    {
        switch ($request->action) {
            case 1: // Nuevo cambio de estado de documento
                $response = $this->cambiarEstadoDocumento( $request );
                break;
            case 2: // Editar cambio de estado de documento
                $response = $this->editarEstadoDocumento( $request );
                break;
            case 3: // Marcar documento como importante
                $response = $this->marcarDocumentoImportante( $request );
                break;
            case 4: // Marcar documento como archivado
                $response = $this->marcarDocumentoArchivado( $request );
                break;
            case 5: // Asignar número de expediente a denuncia
                $response = $this->asignarNoExpedienteDenuncia( $request );
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
        $documento = MDocumento::find( $request->documento );

        // Recuperar las direcciones asignadas al usuario
        $ids_direcciones_origen = session('DireccionesKeys');

        // Recuperar los departamentos asignados al usuario
        $ids_departamentos_origen = session('DepartamentosKeys');
        
        $data = [
            'title'         => 'Cambio de Estado de Documento',
            'url_send_form' => url('panel/documentos/manager'),
            'form_id'       => 'form-cambio-estado-documento',
            'action'        => 1,
            'documento'     => $documento->getKey(),
        ];

        // Buscar el último seguimiento del documento
        $seguimiento = $documento->Seguimientos()->with('DireccionOrigen','DepartamentoOrigen','DireccionDestino','DepartamentoDestino')->get()->last();

        $data['direccion_origen']    = $seguimiento->DireccionDestino->getNombre();

        $data['departamento_origen'] = '';

        if( $seguimiento->DepartamentoDestino ) {
            $data['departamento_origen'] = $seguimiento->DepartamentoDestino->getNombre();
        }

        // Obtener todas las direcciones de destino existentes y disponibles con sus departamentos existentes y disponibles
        $direcciones = MDireccion::with('DepartamentosExistentesDisponibles')
                       ->select('DIRE_DIRECCION','DIRE_NOMBRE')
                       ->siExistenteDisponible()
                       ->orderBy('DIRE_NOMBRE')
                       ->get();

        $data['direcciones_destino'] = $direcciones->pluck('DIRE_NOMBRE','DIRE_DIRECCION')->toArray();
        
        // Obtener todos los departamentos de destino disponibles
        $data['departamentos_destino'] = [];

        foreach ($direcciones as $direccion) {
            foreach ($direccion->DepartamentosExistentesDisponibles as $departamento) {
                $data['departamentos_destino'][] = [
                    $direccion->getKey(),
                    $departamento->getKey(),
                    $departamento->getNombre()
                ];
            }
        }

        $data['system_estados_documentos'][1] = 'En seguimiento';
        $data['dispersiones'][1]              = 'Normal';

        if ( user()->can('DOC.RECHAZAR') ) {
            $data['system_estados_documentos'][2] = 'Rechazar documento';
        }

        if ( user()->can('DOC.FINALIZAR') ) {
            $data['system_estados_documentos'][3] = 'Finalizar documento (resolver)';
            $data['dispersiones'][2]              = 'Múltiple (varios destinos)';
        }

        // Obtener los estados de documentos de sus direcciones y departamentos
        $estados = MEstadoDocumento::select('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO')->siExistenteDisponible()
                   ->where(function($query) use ($seguimiento){
                        $query->where(function($query) use($seguimiento){
                            $query->where('ESDO_DIRECCION',$seguimiento->DireccionDestino->getKey());
                            $query->whereNull('ESDO_DEPARTAMENTO');
                        });

                        if( $seguimiento->DepartamentoDestino )
                            $query->orWhere('ESDO_DEPARTAMENTO',$seguimiento->DepartamentoDestino->getKey());
                    })
                   ->pluck('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO')
                   ->toArray();

        $data['estados'] = $estados;

        // Verificamos si hay solicitud de contestación al documento
        $semaforo = MDocumentoSemaforizado::where('DOSE_DOCUMENTO',$seguimiento->SEGU_DOCUMENTO)
                   ->whereIn('DOSE_ESTADO',[1,2]) // En espera, No atendido
                   ->whereNull('DOSE_SEGUIMIENTO_B')
                   ->get()
                   ->first();
        
        $data['contestar'] = $semaforo;

        $origen_solicitud = $seguimiento->DireccionOrigen->getNombre();

        if( $seguimiento->DepartamentoOrigen ) {
            $origen_solicitud .= ', ' . $seguimiento->DepartamentoOrigen->getNombre();
        }

        $data['origen_solicitud'] = $origen_solicitud;

        return view('Panel.Documentos.formCambioEstadoDocumento')->with($data);
    }

    // Método para cambiar el estado de un documento
    public function cambiarEstadoDocumento( $request )
    {
        try {
            DB::beginTransaction();

            $documento = MDocumento::find( $request->documento );

            if ($documento->finalizado()) {
                return $this->responseErrorJSON('<i class="fa fa-fw fa-warning"></i> El documento ya fue finalizado. No se permiten más cambios de estado.');
            }

            if ($documento->rechazado()) {
                return $this->responseErrorJSON('<i class="fa fa-fw fa-warning"></i> El documento fue rechazado. No se permiten más cambios de estado.');
            }

            $documentoSemaforizado = MDocumentoSemaforizado::where('DOSE_DOCUMENTO',$documento->getKey())
                   ->whereIn('DOSE_ESTADO',[1,2]) // En espera, No atendido
                   ->whereNull('DOSE_SEGUIMIENTO_B')
                   ->limit(1)->first();

            if ( $documentoSemaforizado && ! $request->has('contestacion') ) {
                return $this->responseErrorJSON('<i class="fa fa-fw fa-warning"></i> Debe contestar a la solicitud realizada por el origen anterior.');
            }

            if($request->estado_documento == 1 && $request->get('semaforizar') == 1 && !user()->can('SEG.ADMIN.SEMAFORO') ) // Permitir semaforizar el documento, si aun estará en seguimiento y si el usuario tiene el permiso
            {
                abort(403);
            }

            switch ($request->estado_documento) {
                case 1:
                    $documento->DOCU_SYSTEM_ESTADO_DOCTO = 3; // Documento en seguimiento
                    $documento->save();
                    break;
                case 2:
                    $documento->DOCU_SYSTEM_ESTADO_DOCTO = 5; // Documento rechazado
                    $documento->save();
                    break;
                case 3:
                    $documento->DOCU_SYSTEM_ESTADO_DOCTO = 4; // Documento resuelto/finalizado
                    $documento->save();
                    break;
                default:
                    break;
            }

            $direccion_destino = $request->get('direccion_destino',0);
            if ($direccion_destino == 0) {
                $direccion_destino = null;
            }

            $departamento_destino = $request->get('departamento_destino',0);
            if ($departamento_destino == 0) {
                $departamento_destino = null;
            }

            $ultimoSeguimiento = $documento->Seguimientos->last();

            $seguimientoNuevo = new MSeguimiento;
            $seguimientoNuevo->SEGU_USUARIO              = userKey();
            $seguimientoNuevo->SEGU_DOCUMENTO            = $ultimoSeguimiento->getDocumento();
            $seguimientoNuevo->SEGU_DIRECCION_ORIGEN     = $ultimoSeguimiento->getDireccionDestino();
            $seguimientoNuevo->SEGU_DEPARTAMENTO_ORIGEN  = $ultimoSeguimiento->getDepartamentoDestino();
            $seguimientoNuevo->SEGU_DIRECCION_DESTINO    = $direccion_destino;
            $seguimientoNuevo->SEGU_DEPARTAMENTO_DESTINO = $departamento_destino;
            $seguimientoNuevo->SEGU_ESTADO_DOCUMENTO     = $request->estado;
            $seguimientoNuevo->SEGU_LEIDO                = array();
            $seguimientoNuevo->SEGU_OBSERVACION          = $request->observacion;
            $seguimientoNuevo->SEGU_INSTRUCCION          = $request->instruccion;
            $seguimientoNuevo->save();

            if ( $documentoSemaforizado && $request->has('contestacion') ) {
                $documentoSemaforizado->DOSE_ESTADO              = 3; // Respondido
                $documentoSemaforizado->DOSE_SEGUIMIENTO_B       = $seguimientoNuevo->getKey();
                $documentoSemaforizado->DOSE_RESPUESTA           = $request->contestacion;
                $documentoSemaforizado->DOSE_RESPUESTA_FECHA     = Carbon::now();
                $documentoSemaforizado->save();

                // Crear la notificación sobre que se ha respondido a un documento semaforizado
                $data = [
                    'contenido' => sprintf('Ha recibido respuesta al documento <b>#%s</b> con semaforización <b>#%s</b>',
                                    $documento->getFolio(),$documentoSemaforizado->getCodigo()),
                    'url'       => sprintf('panel/documentos/semaforizados/?view=%s&open=2',$documentoSemaforizado->getKey()),
                ];
                
                NotificacionController::nuevaNotificacion('DOC.SEM.RES',$data);
            }

            $notificacion_adicional = '';
            $notificacion_codigo    = 'PAN.TRA.NUE.DOC.REC';

            if ($request->dispersion == 1 && $documento->enSeguimiento()) // Si la dispersión es normal y el documento aun estará en seguimiento
            {
                if( $request->get('semaforizar') == 1 && user()->can('SEG.ADMIN.SEMAFORO') ) // Si el usuario tiene permiso de semaforizar documentos
                {
                    // Calculamos la fecha límite para responder a la solicitud de contestación
                    $fecha_limite = Carbon::now()->addDays( config_var('Sistema.Dias.Limite.Semaforo') )->format('Y-m-d');

                    $semaforo = new MDocumentoSemaforizado;
                    $semaforo->DOSE_DOCUMENTO     = $documento->getKey();
                    $semaforo->DOSE_USUARIO       = userKey();
                    $semaforo->DOSE_ESTADO        = 1; // En espera de contestación
                    $semaforo->DOSE_SOLICITUD     = $request->instruccion;
                    $semaforo->DOSE_FECHA_LIMITE  = $fecha_limite;
                    $semaforo->DOSE_SEGUIMIENTO_A = $seguimientoNuevo->getKey();
                    $semaforo->save();

                    // Le añadimos a la notificación "que el documento se encuentra semaforizado"
                    $notificacion_adicional = ' y se encuentra semaforizado';
                    $notificacion_codigo    = 'PAN.TRA.DOC.SEM.REC';
                }
            }
            elseif ($request->dispersion == 2 && $documento->finalizado()) // Si la dispersión es múltiple y el documento ya se finalizará
            {
                foreach ($request->get('direcciones',[]) as $direccion) {
                    $seguimientoDispersion = new MSeguimientoDispersion;
                    $seguimientoDispersion->SEDI_SEGUIMIENTO       = $seguimientoNuevo->getKey();
                    $seguimientoDispersion->SEDI_DOCUMENTO         = $documento->getKey();
                    $seguimientoDispersion->SEDI_SYSTEM_TIPO_DOCTO = $documento->getTipoDocumento();
                    $seguimientoDispersion->SEDI_DIRECCION         = $direccion;
                    $seguimientoDispersion->save();
                }

                foreach ($request->get('departamentos',[]) as $departamento) {

                    $departamento = MDepartamento::find($departamento);

                    $seguimientoDispersion = new MSeguimientoDispersion;
                    $seguimientoDispersion->SEDI_SEGUIMIENTO       = $seguimientoNuevo->getKey();
                    $seguimientoDispersion->SEDI_DOCUMENTO         = $documento->getKey();
                    $seguimientoDispersion->SEDI_SYSTEM_TIPO_DOCTO = $documento->getTipoDocumento();
                    $seguimientoDispersion->SEDI_DIRECCION         = $departamento->getDireccion();
                    $seguimientoDispersion->SEDI_DEPARTAMENTO      = $departamento->getKey();
                    $seguimientoDispersion->save();
                }
            }

            if (! in_array($documento->getEstadoDocumento(),[2,3] ) ) // Documento recepcionado, Documento en seguimiento
            {
                cache()->forget('denunciasAlRecepcionar'); // Lista de denuncias que se pueden elegir en la recepción de documentos
            }

            if ( $documento->enSeguimiento() ) {
                $data = [
                    'contenido'    => sprintf('Ha recibido el documento <b>#%s</b> en su panel de trabajo' . $notificacion_adicional, $documento->getFolio()),
                    'direccion'    => $seguimientoNuevo->getDireccionDestino(),
                    'departamento' => $seguimientoNuevo->getDepartamentoDestino(),
                    'url'          => sprintf('panel/documentos/seguimiento?search=%d&read=1',$seguimientoNuevo->getKey()),
                ];
                
                NotificacionController::nuevaNotificacion($notificacion_codigo,$data);
            }

            DB::commit();

            $message = sprintf('<i class="fa fa-fw fa-flash"></i> Seguimiento <b>#%s</b> creado',$seguimientoNuevo->getCodigo());

            return $this->responseSuccessJSON($message);
            
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON($error->getMessage());
        }

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
            $documento = MDocumento::find( $request->documento );
            $documento->marcarImportante();
            $documento->save();

            $importante = $documento->importante();

            $message = sprintf('Documento <b>#%s</b> importante <i class="fa fa-fw fa-star"></i>', $documento->getFolio());

            return $this->responseWarningJSON(['message'=>$message,'importante'=>$importante]);

        } catch(Exception $error) {

        }

    }

    // Método para marcar como Archivado un documento para el usuario
    public function marcarDocumentoArchivado( $request )
    {
        try {
            $documento = MDocumento::find( $request->documento );
            $documento->marcarArchivado();
            $documento->save();

            if( $documento->archivado() ){
                $message = sprintf('Documento <b>#%s</b> archivado <i class="fa fa-fw fa-archive"></i>', $documento->getFolio());
                return $this->responseInfoJSON(['message'=>$message,'archivado'=>true]);
            }
            
            return $this->responseTypeJSON(['archivado'=>false]);
        } catch(Exception $error) {

        }

    }

    public function formAsignarNoExpedienteDenuncia(Request $request)
    {
        $data = [
            'title'         => 'Nó. de expediende de denuncia',
            'url_send_form' => url('panel/documentos/manager'),
            'form_id'       => 'form-no-expediente-denuncia',
            'action'        => 5,
            'id'            => $request->id,
        ];

        $denuncia = MDenuncia::where('DENU_DOCUMENTO',$request->id)->first();

        if (is_null($denuncia))
        {
            abort(404);
        }

        $data['no_expediente'] = $denuncia->getNoExpediente();

        return view('Panel.Documentos.formAsignarNoExpedienteDenuncia')->with($data);
    }

    public function asignarNoExpedienteDenuncia( $request )
    {
        $documento = MDocumento::with('Denuncia')->find( $request->id );

        $documento->Denuncia->DENU_NO_EXPEDIENTE = $request->expediente;
        $documento->Denuncia->save();

        cache()->forget('denunciasAlRecepcionar'); // Lista de denuncias que se pueden elegir en la recepción de documentos pueden elegir en la recepción de documentos

        $message = sprintf('Nó. expediente <b>%s</b> asignado a Documento <b>#%s</b>',$documento->Denuncia->getNoExpediente(), $documento->getFolio());

        return $this->responseSuccessJSON($message);
    }

}