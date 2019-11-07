<?php

namespace App\Http\Controllers\Configuracion\System;

use App\Http\Requests\SystemTipoDocumentoRequest;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Dashboard\NotificacionController;

/* Models */
use App\Model\System\MSystemTipoDocumento;
use App\DataTables\SystemTiposDocumentosDataTable;

use Illuminate\Http\Request;

/**
 * Controlador para gestionar los tipos de documentos del sistema
 */
class SystemTipoDocumentoController extends BaseController
{
    private $form_id = 'form-tipo-documento';

    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this->setLog('SystemTipoDocumentoController.log');
    }

    /**
     * Método para retornar la página principal de gestión de los tipos de documentos del sistema.
     */
    public function index(SystemTiposDocumentosDataTable $dataTables)
    {
        $data['table']    = $dataTables;
        $data['form_id']  = $this->form_id;
        $data['form_url'] = url('configuracion/sistema/tipos-documentos/nuevo');

        return view('Configuracion.Sistema.TipoDocumento.indexTipoDocumento')->with($data);
    }

    /**
     * Método para administrar las peticiones que recibe el controlador
     */
    public function manager(SystemTipoDocumentoRequest $request)
    {
        switch ($request->action) {
            case 1: // Nuevo
                $response = $this->nuevoTipoDocumento( $request );
                break;
            case 2: // Editar
                $response = $this->editarTipoDocumento( $request );
                break;
            case 3: // Visualizar tipo de documento
                $response = $this->verTipoDocumento( $request );
                break;
            case 4: // Activar / Desactivar
                $response = $this->activarTipoDocumento( $request );
                break;
            case 5: // Eliminar tipo de documento
                $response = $this->eliminarTipoDocumento( $request );
                break;
            default:
                return response()->json(['message'=>'Petición no válida'],404);
                break;
        }

        return $response;
    }

    /**
     * Método para devolver los registros que llenarán la tabla de la página principal
     */
    public function postDataTable(SystemTiposDocumentosDataTable $dataTables)
    {
        return $dataTables->getData();
    }

    /**
     * Método para retornar el formulario para la creación de un nuevo tipo de documento
     */
    public function formNuevoTipoDocumento()
    {
        try {
            $data['title']         = 'Nuevo tipo de documento';
            $data['form_id']       = $this->form_id;
            $data['url_send_form'] = url('configuracion/sistema/tipos-documentos/manager');
            $data['action']        = 1;
            $data['model']         = null;
            $data['colores']       = MSystemTipoDocumento::getListaRibbonColor();
            $data['id']            = null;

            return view('Configuracion.Sistema.TipoDocumento.formTipoDocumento')->with($data);
        } catch(Exception $error) {

        }
    }

    /**
     * Método para guardar un nuevo tipo de documento
     */
    public function nuevoTipoDocumento( $request )
    {
        try {
            $tipoDocumento = new MSystemTipoDocumento;
            $tipoDocumento->SYTD_NOMBRE          = $request->nombre;
            $tipoDocumento->SYTD_ETIQUETA_NUMERO = $request->etiqueta;
            $tipoDocumento->SYTD_CODIGO_ACUSE    = $request->codigo;
            $tipoDocumento->SYTD_RIBBON_COLOR    = $request->color;
            $tipoDocumento->save();

            cache()->forget('tiposDocumentosExistentesDisponibles');

            // Crear la notificación para usuarios del sistema
            $data = [
                'contenido'  => sprintf('Nuevo tipo de documento #%s <b>%s</b> creado ', $tipoDocumento->getCodigo(), $tipoDocumento->getNombre()),
            ];
            
            NotificacionController::nuevaNotificacion('REC.LOC.NUE.TIP.DOC',$data);
            NotificacionController::nuevaNotificacion('REC.FOR.NUE.TIP.DOC',$data);

            // Lista de tablas que se van a recargar automáticamente
            $tables = 'dataTableBuilder';

            $message = sprintf('<i class="fa fa-fw fa-files-o"></i> Tipo de documento <b>%s</b> creado',$tipoDocumento->getCodigo());
            return $this->responseSuccessJSON($message,$tables);

        } catch(Exception $error) {
            return response()->json(['status'=>false,'message'=>'Ocurrió un error al crear el tipo de documento. Error ' . $error->getMessage() ]); 
        }
    }

    /**
     * Método para retornar el formulario para editar un tipo de documento
     */
    public function formEditarTipoDocumento(Request $request)
    {
        try {
            $data['title']         = 'Editar tipo de documento';
            $data['form_id']       = $this->form_id;
            $data['url_send_form'] = url('configuracion/sistema/tipos-documentos/manager');
            $data['action']        = 2;
            $data['model']         = MSystemTipoDocumento::findOrFail( $request->id );
            $data['colores']       = MSystemTipoDocumento::getListaRibbonColor();
            $data['id']            = $request->id;

            return view('Configuracion.Sistema.TipoDocumento.formTipoDocumento')->with($data);
        } catch(Exception $error) {

        }
    }

    /**
     * Método para guardar los cambios realizados a un tipo de documento
     */
    public function editarTipoDocumento( $request )
    {
        try {
            $tipoDocumento = MSystemTipoDocumento::findOrFail( $request->id );
            $tipoDocumento->SYTD_NOMBRE          = $request->nombre;
            $tipoDocumento->SYTD_ETIQUETA_NUMERO = $request->etiqueta;
            $tipoDocumento->SYTD_CODIGO_ACUSE    = $request->codigo;
            $tipoDocumento->SYTD_RIBBON_COLOR    = $request->color;
            $tipoDocumento->save();

            cache()->forget('tiposDocumentosExistentesDisponibles');

            // Lista de tablas que se van a recargar automáticamente
            $tables = 'dataTableBuilder';

            $message = sprintf('<i class="fa fa-fw fa-files-o"></i> Tipo de documento <b>%s</b> modificado',$tipoDocumento->getCodigo());
            
            return $this->responseSuccessJSON($message,$tables);
        } catch(Exception $error) {
            return response()->json(['status'=>false,'message'=>'Ocurrió un error al editar el tipo de documento. Error ' . $error->getMessage() ]);
        }
    }

    /**
     * Método para consultar la información de un tipo de documento del sistema
     */
    public function verTipoDocumento( $request )
    {
        try {
            $tipoDocumento = MSystemTipoDocumento::findOrFail( $request->id );
            $data['title'] = sprintf('Tipo de Documento #%s', $tipoDocumento->getCodigo() );

            $data['detalles'] = [
                ['Código',   $tipoDocumento->getCodigo()],
                ['Nombre',   $tipoDocumento->getNombre()],
                ['Color',    $tipoDocumento->presenter()->getBadge()],
                ['Fecha',    $tipoDocumento->presenter()->getFechaCreacion()]
            ];

            return view('Configuracion.Sistema.TipoDocumento.verTipoDocumento')->with($data);
        } catch(Exception $error) {

        }
    }

    /**
     * Método para activar o desactivar un tipo de documento indicado
     */
    public function activarTipoDocumento( $request )
    {
        try {
            $tipoDocumento = MSystemTipoDocumento::findOrFail( $request->id );
            $tipoDocumento->cambiarDisponibilidad()->save();

            cache()->forget('tiposDocumentosExistentesDisponibles');

            if( $tipoDocumento->disponible() ){
                $message = sprintf('<i class="fa fa-fw fa-check"></i> Tipo de documento <b>%s</b> activado',$tipoDocumento->getCodigo());
                return $this->responseInfoJSON($message);
            }else{

                // Crear la notificación para usuarios del sistema
                $data = [
                    'contenido'  => sprintf('Tipo de documento #%s <b>%s</b> ha sido desactivado', $tipoDocumento->getCodigo(), $tipoDocumento->getNombre()),
                ];
                
                NotificacionController::nuevaNotificacion('REC.LOC.TIP.DOC.DES',$data);
                NotificacionController::nuevaNotificacion('REC.FOR.TIP.DOC.DES',$data);

                $message = sprintf('<i class="fa fa-fw fa-warning"></i> Tipo de documento <b>%s</b> desactivado',$tipoDocumento->getCodigo());
                return $this->responseWarningJSON($message);
            }

        } catch(Exception $error) {
            return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
        }
    }

    /**
     * Método para realizar la eliminación de un tipo de documento
     */
    public function eliminarTipoDocumento( $request )
    {
        try {
            if( $request->id == 1 || $request->id == 2 ) // No permitir la eliminación del tipo de documento "Denuncia" ni "Documento de denuncia"
            {
                return $this->responseErrorJSON('<i class="fa fa-fw fa-warning"></i> No es posible eliminar el tipo de documento.');
            }

            $tipoDocumento = MSystemTipoDocumento::findOrFail( $request->id );
            $tipoDocumento->eliminar()->save();

            cache()->forget('tiposDocumentosExistentesDisponibles');

            // Crear la notificación para usuarios del sistema
            $data = [
                'contenido'  => sprintf('Tipo de documento #%s <b>%s</b> ha sido eliminado', $tipoDocumento->getCodigo(), $tipoDocumento->getNombre()),
            ];
            
            NotificacionController::nuevaNotificacion('REC.LOC.TIP.DOC.ELI',$data);
            NotificacionController::nuevaNotificacion('REC.FOR.TIP.DOC.ELI',$data);

            $tables = 'dataTableBuilder';

            // Lista de tablas que se van a recargar automáticamente
            $message = sprintf('<i class="fa fa-fw fa-warning"></i> Tipo de documento <b>%s</b> eliminado',$tipoDocumento->getCodigo());

            return $this->responseWarningJSON($message,'danger',$tables);
        } catch(Exception $error) {
            return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el Tipo de documento. Error ' . $error->getMessage() ]);
        }
    }

}