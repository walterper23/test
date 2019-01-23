<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use App\Http\Requests\RecepcionForaneaRequest;
use Carbon\Carbon;
use Exception;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Dashboard\NotificacionController;

/* Models */
use App\Model\MAcuseRecepcion;
use App\Model\MArchivo;
use App\Model\MDenuncia;
use App\Model\MDetalle;
use App\Model\MDocumento;
use App\Model\MDocumentoForaneo;
use App\Model\MDocumentoDenuncia;
use App\Model\MEscaneo;
use App\Model\MMunicipio;
use App\Model\MSeguimiento;
use App\Model\Catalogo\MAnexo;
use App\Model\System\MSystemTipoDocumento;
use App\Model\System\MSystemConfig;

/* DataTables */
use App\DataTables\DenunciasForaneasDataTable;
use App\DataTables\DocumentosDenunciasForaneasDataTable;
use App\DataTables\DocumentosForaneosDataTable;

class RecepcionForaneaController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('canAtLeast:REC.VER.FORANEO,REC.DOCUMENTO.FORANEO',['only' => ['index','postDataTable']]);
        $this->setLog('RecepcionForaneaController.log');
    }

    public function index(Request $request)
    {
        $tabla1 = new DenunciasForaneasDataTable();
        $tabla2 = new DocumentosDenunciasForaneasDataTable();
        $tabla3 = new DocumentosForaneosDataTable();

        $data['table1'] = $tabla1;
        $data['table2'] = $tabla2;
        $data['table3'] = $tabla3;

        $view  = $request->get('view','denuncias');
        $acuse = $request->get('acuse');

        $data['tab_1'] = '';
        $data['tab_2'] = '';
        $data['tab_3'] = '';
        $data['tab_4'] = '';

        switch ( $view ) {
            case 'denuncias':
                $data['tab_1'] = ' active';
                break;
            case 'documentos-denuncias':
                $data['tab_2'] = ' active';
                break;
            case 'documentos':
                $data['tab_3'] = ' active';
                break;
            case 'recepciones-foraneas':
                $data['tab_4'] = ' active';
                break;
            default:
                $data['tab_1'] = ' active';
                break;
        }

        $data['acuse'] = $acuse;

        return view('Recepcion.indexRecepcionForanea')->with($data);
    }

    public function manager(RecepcionForaneaRequest $request)
    {
        switch ($request->action) {
            case 0: // Guardar recepción en captura
                $response = $this->capturarRecepcion( $request );
                break;
            case 1: // Nueva recepción
                $response = $this->nuevaRecepcion( $request );
                break;
            case 2: // Editar
                $response = $this->editarRecepcion( $request );
                break;
            case 3: // Visualizar recepción
                $response = $this->verRecepcion( $request );
                break;
            case 4: // Eliminar
                $response = $this->eliminarRecepcion( $request );
                break;
            case 5: // Finalizar captura de recepción foránea
                $response = $this->finalizarRecepcion( $request );
                break;
            case 6: // Enviar documento a Oficialía de Partes Local
                $response = $this->enviarDocumento( $request );
                break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }
        return $response;
    }

    public function postDataTable(Request $request)
    {
        $type = $request->get('type','denuncias');

        switch ($type) {
            case 'denuncias' :
                $dataTables = new DenunciasForaneasDataTable;
                break;
            case 'documentos-denuncias':
                $dataTables = new DocumentosDenunciasForaneasDataTable;
                break;
            case 'documentos':
                $dataTables  = new DocumentosForaneosDataTable;
                break;
            default:
                $dataTables  = new DocumentosForaneosDataTable;
                break;
        }

        return $dataTables->getData();
    }

    // Método para guardar un documento en estado de captura pendiente, o sea, aun no recepcionado completamente
    public function capturarRecepcion( $request )
    {

    }

    public function documentosEnCaptura()
    {
        return abort(404);
    }

    public function formNuevaRecepcion(Request $request)
    {
        $data = [];

        // Recuperamos los tipos de documento->Detalle que se pueden recepcionar
        $tiposDocumentosExistentesDisponibles = cache()->rememberForever('tiposDocumentosExistentesDisponibles',function(){
            return MSystemTipoDocumento::existenteDisponible()->get();
        });

        $data['tipos_documentos'] = $tiposDocumentosExistentesDisponibles;

        $denunciasAlRecepcionar = cache()->rememberForever('denunciasAlRecepcionar',function(){
            return MDenuncia::select('DENU_DENUNCIA','DENU_NO_EXPEDIENTE')
                   ->join('documentos','DENU_DOCUMENTO','=','DOCU_DOCUMENTO')
                   ->whereNotNull('DENU_NO_EXPEDIENTE') // Número de expediente ya asignado
                   ->whereIn('DOCU_SYSTEM_ESTADO_DOCTO',[2,3]) // Documento recepcionado, Documento en seguimiento
                   ->get();
        });

        $data['denuncias'] = $denunciasAlRecepcionar->pluck('DENU_NO_EXPEDIENTE','DENU_DENUNCIA')->toArray();

        // Recuperamos los anexos almacenados en el sistema
        $anexosExistentesDisponibles = cache()->rememberForever('anexosExistentesDisponibles',function(){
            return MAnexo::existenteDisponible()->get();
        });

        $data['anexos'] = $anexosExistentesDisponibles->sortBy('ANEX_NOMBRE')->pluck('ANEX_NOMBRE','ANEX_ANEXO')->toArray();

        $cacheMunicipios = cache()->rememberForever('municipiosDisponibles',function(){
            return MMunicipio::disponible()->get();
        });

        $data['municipios'] = $cacheMunicipios->sortBy('MUNI_CLAVE')->mapWithKeys(function($item){
            return [ $item->getKey() => sprintf('%s :: %s',$item->getClave(),$item->getNombre()) ];
        })->toArray();

        $data['panel_titulo']          = 'Nueva recepción foránea';
        $data['context']               = 'context-form-recepcion-foranea';
        $data['form_id']               = 'form-recepcion-foranea';
        $data['url_send_form']         = url('recepcion/documentos-foraneos/manager');
        $data['url_send_form_escaneo'] = url('recepcion/documentos-foraneos/nuevo-escaneo');

        $data['form'] = view('Recepcion.formNuevaRecepcion')->with($data);

        unset($data['tipos_documentos']);

        return view('Recepcion.nuevaRecepcion')->with($data);
    }

    // Método para realizar el guardado y la recepción de un documento foráneo
    public function nuevaRecepcion( $request )
    {
        try {
            $recepcion = new RecepcionController;
            
            DB::beginTransaction();

            // Mandamos a recepcionar el documento
            $documento = $recepcion->procesoRecepcionar($request,2); // 2 = Tipo recepción foránea

            // Si el documento es foráneo, guardamos el registro foráneo
            if( $documento->isForaneo() )
            {
                $documentoForaneo = new MDocumentoForaneo;
                $documentoForaneo->DOFO_SYSTEM_TIPO_DOCTO   = $documento->getTipoDocumento();
                $documentoForaneo->DOFO_DETALLE             = $documento->getDetalle();
                $documentoForaneo->DOFO_ENVIADO             = 0; // Documento foráneo aún no Enviado a Oficialía de partes
                $documentoForaneo->DOFO_RECIBIDO            = 0; // Documento foráneo aún no Recibido por Oficialía de partes
                $documentoForaneo->DOFO_VALIDADO            = 0; // Documento foráneo aún no Validado por Oficialía de partes
                $documentoForaneo->DOFO_RECEPCIONADO        = 0; // Documento foráneo aún no Recepcionado por Oficialía de partes
                $documentoForaneo->DOFO_DOCUMENTO_LOCAL     = $documento->getKey();
                $documentoForaneo->save();
            }
            
            DB::commit();

            return $this->responseSuccessJSON(['documento'=>$documento->getKey(),'tipo'=>'foraneo']);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON( $error->getMessage() );
        }
    }

    public function formEditarRecepcion(Request $request)
    {
        $documento = $request->get('search');
        $documento = MDocumentoForaneo::findOrFail($documento);

        $data = [];

        // Recuperamos los tipos de documento->Detalle que se pueden recepcionar
        $tiposDocumentosExistentesDisponibles = cache()->rememberForever('tiposDocumentosExistentesDisponibles',function(){
            return MSystemTipoDocumento::existenteDisponible()->get();
        });

        $data['tipos_documentos'] = $tiposDocumentosExistentesDisponibles;

        $denunciasAlRecepcionar = cache()->rememberForever('denunciasAlRecepcionar',function(){
            return MDenuncia::select('DENU_DENUNCIA','DENU_NO_EXPEDIENTE')
                   ->join('documentos','DENU_DOCUMENTO','=','DOCU_DOCUMENTO')
                   ->whereNotNull('DENU_NO_EXPEDIENTE') // Número de expediente ya asignado
                   ->whereIn('DOCU_SYSTEM_ESTADO_DOCTO',[2,3]) // Documento recepcionado, Documento en seguimiento
                   ->get();
        });

        $data['denuncias'] = $denunciasAlRecepcionar->pluck('DENU_NO_EXPEDIENTE','DENU_DENUNCIA')->toArray();

        // Recuperamos los anexos almacenados en el sistema
        $anexosExistentesDisponibles = cache()->rememberForever('anexosExistentesDisponibles',function(){
            return MAnexo::existenteDisponible()->get();
        });

        $data['anexos'] = $anexosExistentesDisponibles->sortBy('ANEX_NOMBRE')->pluck('ANEX_NOMBRE','ANEX_ANEXO')->toArray();

        $cacheMunicipios = cache()->rememberForever('municipiosDisponibles',function(){
            return MMunicipio::disponible()->get();
        });

        $data['municipios'] = $cacheMunicipios->sortBy('MUNI_CLAVE')->mapWithKeys(function($item){
            return [ $item->getKey() => sprintf('%s :: %s',$item->getClave(),$item->getNombre()) ];
        })->toArray();

        $data['panel_titulo']          = 'Editar recepción foránea';
        $data['context']               = 'context-form-editar-recepcion-foranea';
        $data['form_id']               = 'form-editar-recepcion-foranea';
        $data['url_send_form']         = url('recepcion/documentos-foraneos/manager');
        $data['url_send_form_escaneo'] = url('recepcion/documentos-foraneos/nuevo-escaneo');
        $data['documento']             = $documento;
        $data['detalle']               = $documento->Detalle;

        $data['form'] = view('Recepcion.formEditarRecepcion')->with($data);

        unset($data['tipos_documentos']);

        return view('Recepcion.editarRecepcion')->with($data);
    }

    // Método para realizar la modificación de una recepción
    public function editarRecepcion( $request )
    {
        try {
            $tipo_documento = MSystemTipoDocumento::find( $request->tipo_documento );

            $fecha_recepcion = $request->recepcion;

            DB::beginTransaction();

            // Buscamos el documento
            $documento = MDocumentoForaneo::findOrFail($request->id);
            $documento->DOFO_SYSTEM_TIPO_DOCTO   = $tipo_documento->getKey();
            $documento->DOFO_NUMERO_DOCUMENTO    = $request->numero;
            $documento->save();

            // Guardamos los detalles del documento
            $detalle = $documento->Detalle;
            $detalle->DETA_MUNICIPIO              = $request->municipio;
            $detalle->DETA_FECHA_RECEPCION        = $fecha_recepcion;
            $detalle->DETA_DESCRIPCION            = $request->descripcion;
            $detalle->DETA_RESPONSABLE            = $request->responsable;
            $detalle->DETA_ANEXOS                 = $request->anexos;
            $detalle->DETA_OBSERVACIONES          = $request->observaciones;
            $detalle->DETA_ENTREGO_NOMBRE         = $request->nombre;
            $detalle->DETA_ENTREGO_EMAIL          = $request->e_mail;
            $detalle->DETA_ENTREGO_TELEFONO       = $request->telefono;
            $detalle->DETA_ENTREGO_IDENTIFICACION = $request->identificacion;
            $detalle->save();

            if ($tipo_documento->getKey() == 1) // Si el tipo de documento es denuncia ...
            {
                $documento->DOFO_NUMERO_DOCUMENTO = null;
                $documento->save();
            }
            else if ($tipo_documento->getKey() == 2) // Si el tipo de documento es un documento para denuncia ...
            {
                $denuncia = MDenuncia::findOrFail( $request->denuncia );

                $documentoDenuncia = $documento->DocumentoDenuncia;

                if(! $documentoDenuncia ) // Si el documento aún no se encuentra ligado a una denuncia, ligamos el documento a la denuncia
                {
                    $documentoDenuncia = new MDocumentoDenuncia; // ... registramos el documento a la denuncia
                    $documentoDenuncia->DODE_DOCUMENTO_FORANEO = $documento->getKey();
                    $documentoDenuncia->DODE_DETALLE           = $denuncia->Documento->Detalle->getKey();
                    // Relacionamos la recepción del documento-denuncia al último seguimiento del documento original (denuncia)
                    $documentoDenuncia->DODE_SEGUIMIENTO       = $denuncia->Documento->Seguimientos->last()->getKey();
                }

                $documentoDenuncia->DODE_DENUNCIA         = $denuncia->getKey();
                $documentoDenuncia->DODE_DOCUMENTO_ORIGEN = $denuncia->getDocumento();
                $documentoDenuncia->save();
            }

            $fecha_carbon = Carbon::createFromFormat('Y-m-d',$fecha_recepcion);

            $recepcionista = user()->Recepcionista;

            $folio_estructura = $recepcionista->getFolioEstructura();
            $buscar     = ['%anio%','%folio%','%codigo%'];
            $reemplazar = [$fecha_carbon->format('Y'),$documento->getFolio(),$tipo_documento->getCodigoAcuse()];

            $folio_acuse = str_replace($buscar, $reemplazar, $folio_estructura);

            // Sustituimos las diagonales por guiones bajos
            $nombre_acuse_pdf = sprintf('%s.pdf',str_replace('/','_', $folio_acuse));
            
            // Modificamos el registro del acuse de recepción del documento
            $acuse = $documento->AcuseRecepcion;
            $acuse->ACUS_NUMERO    = $folio_acuse;
            $acuse->ACUS_NOMBRE    = $nombre_acuse_pdf;
            $acuse->ACUS_ENTREGO   = $detalle->getEntregoNombre();
            $acuse->save();

            DB::commit();

            return $this->responseSuccessJSON(['documento'=>$documento->getKey(),'tipo'=>'foraneo']);
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseErrorJSON( $error->getMessage() );
        }
    }

    public function finalizarRecepcion($request)
    {
        try{
            $id_documento = $request->get('id');
            $documento = MDocumento::findOrFail($id_documento);

            $acuse = $documento->AcuseRecepcion;

            if ($request->has('acuse') && $request->get('acuse') == 1) // Si el usuario ha indicado que quiere abrir inmediatamente el acuse de recepción
            {
                $url = url( sprintf('recepcion/acuse/documento/%s?d=0',$acuse->getNombre()) );
                $request->session()->flash('urlAcuseAutomatico', $url);
            }
            
            $tipo_documento = $documento->TipoDocumento;


            if ($tipo_documento->getKey() == 1) // Si el tipo de documento es denuncia ...
            {
                $redirect = '?view=denuncias';
                $codigo_preferencia = 'NUE.REC.DEN.FOR';
            }
            else if ( $tipo_documento->getKey() == 2 ) // Si el tipo de documento es un documento para denuncia ...
            {
                $redirect = '?view=documentos-denuncias';
                $codigo_preferencia = 'NUE.REC.DODE.FOR';
            }
            else
            {
                $redirect = '?view=documentos';
                $codigo_preferencia = 'NUE.REC.DOC.FOR';
            }

            // Crear la notificación sobre la recepción de un nuevo documento local
            $data = [
                'contenido' => sprintf('Se ha recepcionado un nuevo documento foráneo #%s de tipo %s', $documento->getFolio(), $tipo_documento->getNombre()),
                'url'       => 'recepcion/documentos-foraneos/recepcionados' . $redirect,
            ];

            // Creamos la notificación para los usuarios que pueden ver las recepciones foráneas            
            NotificacionController::nuevaNotificacion('VER.REC.FOR.NUE.REC.FOR',$data);

            // Mandamos el correo de notificación a los usuarios que tengan la preferencia asignada
            NotificacionController::enviarCorreoSobreNuevaRecepcion($codigo_preferencia, $documento);

            return $this->responseSuccessJSON(url('recepcion/documentos-foraneos/recepcionados' . $redirect));
        } catch(Exception $error) {
            return $this->responseErrorJSON($error->getMessage());
        }
    }

    public function verRecepcion( $request )
    {

    }

    public function eliminarRecepcion( $request )
    {
        try {
            $documento = MDocumentoForaneo::findOrFail( $request->id );
            $documento->eliminar()->save();

            // Lista de tablas que se van a recargar automáticamente
            switch ($documento->getTipoDocumento()) {
                case 1:
                    $tables = 'denuncias-datatable';
                    break;
                case 2:
                    $tables = 'documentos-denuncias-datatable';
                    break;
                default:
                    $tables = 'documentos-datatable';
                    break;
            }

            $message = sprintf('<i class="fa fa-fw fa-warning"></i> Recepción foránea <b>%s</b> eliminada',$documento->getFolio());

            return $this->responseWarningJSON($message,'danger',$tables);
        } catch(Exception $error) {
            return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar la recepción foránea. Error ' . $error->getMessage() ]);
        }
    }

    public function enviarDocumento( $request )
    {
        $documento = MDocumentoForaneo::findOrFail( $request->id );
        $documento->DOFO_ENVIADO       = 1;
        $documento->DOFO_FECHA_ENVIADO = Carbon::now();
        $documento->save();

        $message = sprintf('Documento #<b>%s</b> enviado <i class="fa fa-fw fa-car"></i>',$documento->Documento->getFolio());

        if ($documento->getTipoDocumento() == 1)
        {
            $tables = ['denuncias-datatable',null,true];
            $redirect = '?view=denuncias';
        }
        else if ($documento->getTipoDocumento() == 2)
        {
            $tables = ['documentos-denuncias-datatable',null,true];
            $redirect = '?view=documentos-denuncias';
        }
        else
        {
            $tables = ['documentos-datatable',null,true];
            $redirect = '?view=documentos';
        }

        // Crear la notificación para usuarios del sistema
        $data = [
            'contenido'  => sprintf('Documento foráneo #%s <b>%s</b> ha sido puesto en tránsito. Recuerde recibir el documento.', $documento->Documento->getFolio(), $documento->TipoDocumento->getNombre()),
            'url'        => 'recepcion/documentos/foraneos' . $redirect,
        ];
            
        // Creamos la notificación de que el documento ya está en tránsito
        NotificacionController::nuevaNotificacion('REC.LOC.DOC.FOR.TRA',$data);

        return $this->responseSuccessJSON($message,$tables);
    }

}