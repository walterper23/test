<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\RecepcionLocalRequest;
use Illuminate\Filesystem\Filesystem;
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
use App\Model\MDocumentoDenuncia;
use App\Model\MEscaneo;
use App\Model\MMunicipio;
use App\Model\MSeguimiento;
use App\Model\Catalogo\MAnexo;
use App\Model\System\MSystemTipoDocumento;
use App\Model\System\MSystemConfig;

/* DataTables */
use App\DataTables\DenunciasDataTable;
use App\DataTables\DocumentosDenunciasDataTable;
use App\DataTables\DocumentosDataTable;

class RecepcionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLog('RecepcionController.log');
    }

    public function index(Request $request)
    {
        $tabla1 = new DenunciasDataTable();
        $tabla2 = new DocumentosDenunciasDataTable();
        $tabla3 = new DocumentosDataTable();

        $data['table1'] = $tabla1;
        $data['table2'] = $tabla2;
        $data['table3'] = $tabla3;

        $view  = $request->get('view','denuncias');
        $acuse = $request->get('acuse');

        $data['tab_1'] = '';
        $data['tab_2'] = '';
        $data['tab_3'] = '';

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
            default:
                $data['tab_1'] = ' active';
                break;
        }

        $data['acuse'] = $acuse;

        return view('Recepcion.indexRecepcion')->with($data);
    }

    public function documentosEnCaptura()
    {
        abort(404);
    }

    public function manager(RecepcionLocalRequest $request)
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
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }
        return $response;
    }

    public function postDataTable(Request $request)
    {

        $type = $request->get('type');

        switch ($type) {
            case 'denuncias' :
                $dataTables = new DenunciasDataTable;
                break;
            case 'documentos-denuncias':
                $dataTables = new DocumentosDenunciasDataTable;
                break;
            case 'documentos':
                $dataTables  = new DocumentosDataTable;
                break;
            default:
                $dataTables  = new DocumentosDataTable;
                break;
        }

        return $dataTables->getData();
    }

    // Método para guardar un documento en estado de captura pendiente, o sea, aun no recepcionado completamente
    public function capturarRecepcion( $request )
    {

    }

    public function formNuevaRecepcion()
    {
        $data = [];

        // Recuperamos los tipos de documentsos que se pueden recepcionar
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
            return [ $item->getKey() => sprintf('%s - %s',$item->getClave(),$item->getNombre()) ];
        })->toArray();

        $data['context']       = 'context-form-recepcion';
        $data['form_id']       = 'form-recepcion';
        $data['url_send_form'] = url('recepcion/documentos/manager');

        $data['form'] = view('Recepcion.formNuevaRecepcion')->with($data);

        unset($data['tipos_documentos']);

        return view('Recepcion.nuevaRecepcion')->with($data);

    }


    // Método para realizar el guardado y la recepción de un documento
    public function nuevaRecepcion( $request )
    {
        try {
            // Reemplazamos los saltos de línea, por "\n"
            $anexos = preg_replace('/\r|\n/','\n',$request->anexos);
            // Reemplazamos las "\n" repetidas
            $anexos = str_replace('\n\n','\n',$anexos);

            DB::beginTransaction();

            $tipo_documento = MSystemTipoDocumento::find( $request->tipo_documento );

            // Guardamos los detalles del documento
            $detalle = new MDetalle;
            $detalle->DETA_MUNICIPIO              = $request->municipio;
            $detalle->DETA_FECHA_RECEPCION        = $request->recepcion;
            $detalle->DETA_DESCRIPCION            = $request->descripcion;
            $detalle->DETA_RESPONSABLE            = $request->responsable;
            $detalle->DETA_ANEXOS                 = $anexos;
            $detalle->DETA_OBSERVACIONES          = $request->observaciones;
            $detalle->DETA_ENTREGO_NOMBRE         = $request->nombre;
            $detalle->DETA_ENTREGO_EMAIL          = $request->e_mail;
            $detalle->DETA_ENTREGO_TELEFONO       = $request->telefono;
            $detalle->DETA_ENTREGO_IDENTIFICACION = $request->identificacion;
            $detalle->save();

            // Guardamos el documento
            $documento = new MDocumento;
            $documento->DOCU_FOLIO               = 0;
            $documento->DOCU_SYSTEM_TIPO_DOCTO   = $tipo_documento->getKey();
            $documento->DOCU_SYSTEM_ESTADO_DOCTO = 2; // Documento recepcionado
            $documento->DOCU_TIPO_RECEPCION      = 1; // Recepción local
            $documento->DOCU_DETALLE             = $detalle->getKey();
            $documento->DOCU_NUMERO_DOCUMENTO    = $request->numero;
            $documento->save();

            $fecha_reinicio = config_var('Adicional.Fecha.Reinicio.Folios');

            if (! is_null($fecha_reinicio) && $fecha_reinicio <= date('Y-m-d H:i:s') )
            {
                $documento->DOCU_FOLIO = config_var('Adicional.Folio.Reinicio.Folios');
                $documento->save();

                $fecha_reinicio = MSystemConfig::where('SYCO_VARIABLE','Adicional.Fecha.Reinicio.Folios')->limit(1)->first();
                $fecha_reinicio->SYCO_VALOR = null;
                $fecha_reinicio->save();

                MSystemConfig::setAllVariables(); // Volvemos a cargar la variables de configuración al caché
            }

            // Guardamos el primer seguimiento del documento
            $seguimiento = new MSeguimiento;
            $seguimiento->SEGU_USUARIO              = userKey();
            $seguimiento->SEGU_DOCUMENTO            = $documento->getKey();
            $seguimiento->SEGU_DIRECCION_ORIGEN     = config_var('Sistema.Direcc.Origen');  // Dirección de la recepción, por default
            $seguimiento->SEGU_DEPARTAMENTO_ORIGEN  = config_var('Sistema.Depto.Origen');   // Departamento de la recepción, por default
            $seguimiento->SEGU_DIRECCION_DESTINO    = config_var('Sistema.Direcc.Destino'); // Dirección del procurador, por default
            $seguimiento->SEGU_DEPARTAMENTO_DESTINO = config_var('Sistema.Depto.Destino');  // Departamento del procurador, por default
            $seguimiento->SEGU_ESTADO_DOCUMENTO     = config_var('Sistema.Estado.Recepcion.Seguimiento'); // "Documento recepcionado". Estado de documento inicial para seguimiento por default
            $seguimiento->SEGU_OBSERVACION          = $detalle->getObservaciones();
            $seguimiento->save();

            $folio_acuse = sprintf('ARD/%s/%s/%s/%s/',date('Y'),date('m'),$documento->getFolio(),$tipo_documento->getCodigoAcuse()); // ARD/2018/10/005/DENU/
            
            if ($tipo_documento->getKey() == 1) // Si el tipo de documento es denuncia ...
            {
                $denuncia = new MDenuncia; // ... crear el registro de la denuncia
                $denuncia->DENU_DOCUMENTO = $documento->getKey();
                $denuncia->save();

                $redirect = '?view=denuncias';
                $folio_acuse .= $denuncia->getCodigo(); // ARD/2018/10/005/DENU/001
                $codigo_preferencia = 'NUE.REC.DEN';
            }
            else if ( $tipo_documento->getKey() == 2 ) // Si el tipo de documento es un documento para denuncia ...
            {
                $denuncia = MDenuncia::with('Documento')->find( $request->denuncia );

                $documentoDenuncia = new MDocumentoDenuncia; // ... registramos el documento a la denuncia
                $documentoDenuncia->DODE_DENUNCIA          = $denuncia->getKey();
                $documentoDenuncia->DODE_DOCUMENTO_ORIGEN  = $denuncia->Documento->getKey();
                $documentoDenuncia->DODE_DOCUMENTO_LOCAL   = $documento->getKey();
                $documentoDenuncia->DODE_DETALLE           = $denuncia->Documento->Detalle->getKey();
                // Relacionamos la recepción del documento-denuncia al último seguimiento del documento original (denuncia)
                $documentoDenuncia->DODE_SEGUIMIENTO       = $denuncia->Documento->Seguimientos->last()->getKey();
                $documentoDenuncia->save();

                $redirect = '?view=documentos-denuncias';
                $folio_acuse .= $documentoDenuncia->getCodigo();  // ARD/2018/10/005/DODE/002
                $codigo_preferencia = 'NUE.REC.DODE';
            }
            else
            {
                $redirect = '?view=documentos';
                $folio_acuse .= $documento->getFolio(); // ARD/2018/10/005/DENU/005
                $codigo_preferencia = 'NUE.REC.DOC';
            }

            // Sustituimos las diagonales por guiones bajos
            $nombre_acuse_pdf = sprintf('%s.pdf',str_replace('/','_', $folio_acuse));

            // Creamos el registro del acuse de recepción del documento
            $acuse = new MAcuseRecepcion;
            $acuse->ACUS_NUMERO    = $folio_acuse;
            $acuse->ACUS_NOMBRE    = $nombre_acuse_pdf;
            $acuse->ACUS_DOCUMENTO = $documento->getKey();
            $acuse->ACUS_CAPTURA   = 1; // Documento localmente
            $acuse->ACUS_DETALLE   = $detalle->getKey();
            $acuse->ACUS_USUARIO   = userKey();
            $acuse->ACUS_ENTREGO   = $request->nombre;
            $acuse->ACUS_RECIBIO   = user()->UsuarioDetalle->presenter()->getNombreCompleto();
            $acuse->save();

            // Lista de los nombres de los escaneos
            $escaneo_nombres = $request->escaneo_nombre ?? [];

            // Guardamos los archivos o escaneos que se hayan agregado al archivo
            foreach ($request->escaneo ?? [] as $key => $escaneo) {

                $nombre = $escaneo->getClientOriginalName();
                
                if( isset($escaneo_nombres[$key]) && !empty(trim($escaneo_nombres[$key])) )
                {
                    $nombre = trim($escaneo_nombres[$key]);
                }

                $this->nuevoEscaneo($documento, $escaneo,['escaneo_nombre'=>$nombre]);
            }

            DB::commit();

            if ($request->has('acuse') && $request->acuse == 1) // Si el usuario ha indicado que quiere abrir inmediatamente el acuse de recepción
            {
                $url = url( sprintf('recepcion/acuse/documento/%s?d=0',$acuse->getNombre()) );
                $request->session()->flash('urlAcuseAutomatico', $url);
            }
            
            // Crear la notificación para usuarios del sistema
            $data = [
                'contenido'  => sprintf('Se ha recepcionado un nuevo documento #%s de tipo <b>%s</b>', $documento->getFolio(),$documento->TipoDocumento->getNombre()),
                'direccion'  => $seguimiento->getDireccionDestino(),
                'url'        => sprintf('panel/documentos/seguimiento?search=%d&read=1',$seguimiento->getKey()),
            ];
            
            // Creamos la nueva notificación para el Panel de Trabajo sobre nuevo documento recibido
            NotificacionController::nuevaNotificacion('PAN.TRA.NUE.DOC.REC',$data);

            // Mandamos el correo de notificación a los usuarios que tengan la preferencia asignada
            NotificacionController::enviarCorreoSobreNuevaRecepcion($codigo_preferencia, $documento);

            return $this->responseSuccessJSON(url('recepcion/documentos/recepcionados' . $redirect));
        } catch(Exception $error) {
            DB::rollback();
            return $this->responseDangerJSON( $error->getMessage() );
        }

    }

    public function formEditarRecepcion()
    {

    }

    // Método para realizar la modificación de una recepción
    public function editarRecepcion( $request )
    {

    }

    // Método para agregar un nuevo archivo a un documento
    public function nuevoEscaneo(MDocumento $documento, $file, $data)
    {
        $archivo = new MArchivo;
        $archivo->ARCH_FOLDER   = 'app/escaneos';
        $archivo->ARCH_FILENAME = '';
        $archivo->ARCH_PATH     = '';
        $archivo->ARCH_TYPE     = $file->extension();
        $archivo->ARCH_MIME     = $file->getMimeType();
        $archivo->ARCH_SIZE     = $file->getClientSize();

        $archivo->save();

        $escaneo = new MEscaneo;
        $escaneo->ESCA_ARCHIVO          = $archivo->getKey(); 
        $escaneo->ESCA_DOCUMENTO_LOCAL  = $documento->getKey(); 
        $escaneo->ESCA_NOMBRE           = $data['escaneo_nombre']; 
        $escaneo->save();

        $filename = sprintf('docto_%d_scan_%d_arch_%d_%s.pdf',$documento->getKey(), $escaneo->getKey(), $archivo->getKey(), time());
        
        $file->storeAs('',$filename,'escaneos');
        
        $archivo->ARCH_FILENAME = $filename;
        $archivo->ARCH_PATH     = 'app/escaneos/' . $filename;
        $archivo->save();
    }


    public function verRecepcion( $request )
    {

    }

    public function eliminarRecepcion( $request )
    {
        try {
            $documento = MDocumento::find( $request->id );
            $documento->DOCU_SYSTEM_ESTADO_DOCTO = 6; // Estado de Documento Eliminado
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

            $message = sprintf('<i class="fa fa-fw fa-warning"></i> Recepción <b>%s</b> eliminada',$documento->getFolio());

            return $this->responseWarningJSON($message,'danger',$tables);
        } catch(Exception $error) {
            return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar la recepción. Error ' . $error->getMessage() ]);
        }
    }

}