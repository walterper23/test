<?php
namespace App\Http\Controllers\Configuracion\System;

use Illuminate\Http\Request;
use App\Http\Requests\SystemTipoDocumentoRequest;
use Illuminate\Support\Facades\Input;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\SystemTiposDocumentosDataTable;

/* Models */
use App\Model\System\MSystemTipoDocumento;

class SystemTipoDocumentoController extends BaseController
{
	private $form_id = 'form-tipo-documento';

	public function __construct()
	{
		parent::__construct();
		$this -> setLog('SystemTipoDocumentoController.log');
	}

	public function index(SystemTiposDocumentosDataTable $dataTables)
	{
		$data['table']    = $dataTables;
		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/sistema/tipos-documentos/nuevo');

		return view('Configuracion.Sistema.TipoDocumento.indexTipoDocumento') -> with($data);
	}

	public function manager(SystemTipoDocumentoRequest $request)
	{
		switch ($request -> action) {
			case 1: // Nuevo
				$response = $this -> nuevoTipoDocumento( $request );
				break;
			case 2: // Editar
				$response = $this -> editarTipoDocumento( $request );
				break;
			case 3: // Visualizar tipo de documento
				$response = $this -> verTipoDocumento( $request );
				break;
			case 4: // Activar / Desactivar
				$response = $this -> activarTipoDocumento( $request );
				break;
			case 5: // Eliminar tipo de documento
				$response = $this -> eliminarTipoDocumento( $request );
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}

		return $response;
	}

	public function postDataTable(SystemTiposDocumentosDataTable $dataTables)
	{
		return $dataTables->getData();
	}

	public function formNuevoTipoDocumento()
	{
		try {
			$data['title']         = 'Nuevo tipo de documento';
			$data['form_id']       = $this -> form_id;
			$data['url_send_form'] = url('configuracion/sistema/tipos-documentos/manager');
			$data['action']        = 1;
			$data['model']         = null;
			$data['id']            = null;

			return view('Configuracion.Sistema.TipoDocumento.formTipoDocumento') -> with($data);
		} catch(Exception $error) {

		}
	}

	public function nuevoTipoDocumento( $request )
	{
		try {
			$tipoDocumento = new MSystemTipoDocumento;
			$tipoDocumento -> SYTD_NOMBRE          = $request -> nombre;
			$tipoDocumento -> SYTD_ETIQUETA_NUMERO = 'Nó. Oficio';
			$tipoDocumento -> SYTD_RIBBON_COLOR    = 'default';
			$tipoDocumento -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-file-o"></i> Tipo de documento <b>%s</b> creado',$tipoDocumento -> getCodigo());
			return $this -> responseSuccessJSON($message,$tables);

		} catch(Exception $error) {
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al crear el tipo de documento. Error ' . $error->getMessage() ]);	
		}
	}

	public function formEditarTipoDocumento()
	{
		try {
			$data['title']         = 'Editar tipo de documento';
			$data['form_id']       = $this -> form_id;
			$data['url_send_form'] = url('configuracion/sistema/tipos-documentos/manager');
			$data['action']        = 2;
			$data['model']         = MSystemTipoDocumento::findOrFail( Input::get('id') );
			$data['id']            = Input::get('id');

			return view('Configuracion.Sistema.TipoDocumento.formTipoDocumento') -> with($data);
		} catch(Exception $error) {

		}
	}

	public function editarTipoDocumento( $request )
	{
		try {
			$tipoDocumento = MSystemTipoDocumento::findOrFail( $request -> id );
			$tipoDocumento -> SYTD_NOMBRE = $request -> nombre;
			$tipoDocumento -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-files-o"></i> Tipo de documento <b>%s</b> modificado',$tipoDocumento -> getCodigo());
			
			return $this -> responseSuccessJSON($message,$tables);
		} catch(Exception $error) {
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al editar el tipo de documento. Error ' . $error->getMessage() ]);
		}
	}

	public function verTipoDocumento( $request )
    {
        try {
            $tipoDocumento = MSystemTipoDocumento::find( $request -> id );
            $data['title'] = sprintf('Tipo de Documento #%s', $tipoDocumento -> getCodigo() );

            $data['detalles'] = [
                ['Código', $tipoDocumento -> getCodigo()],
                ['Nombre', $tipoDocumento -> getNombre()],
                ['Fecha',  $tipoDocumento -> presenter() -> getFechaCreacion()]
            ];

            return view('Configuracion.Sistema.TipoDocumento.verTipoDocumento') -> with($data);
        } catch(Exception $error) {

        }

    }

	public function activarTipoDocumento( $request )
	{
		try {
			$tipoDocumento = MSystemTipoDocumento::findOrFail( $request -> id );
			$tipoDocumento -> cambiarDisponibilidad() -> save();
			
			if( $tipoDocumento -> disponible() ){
				$message = sprintf('<i class="fa fa-fw fa-check"></i> Tipo de documento <b>%s</b> activado',$tipoDocumento -> getCodigo());
				return $this -> responseInfoJSON($message);
			}else{
				$message = sprintf('<i class="fa fa-fw fa-warning"></i> Tipo de documento <b>%s</b> desactivado',$tipoDocumento -> getCodigo());
				return $this -> responseWarningJSON($message);
			}

		} catch(Exception $error) {
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	public function eliminarTipoDocumento( $request )
    {
        try {

        	if( $request -> id == 1 || $request -> id == 2 ) // No permitir la eliminación del tipo de documento "Denuncia" ni "Documento de denuncia"
        	{
            	return $this -> responseDangerJSON('<i class="fa fa-fw fa-warning"></i> No es posible eliminar el tipo de documento.');
        	}

            $tipoDocumento = MSystemTipoDocumento::findOrFail( $request -> id );
            $tipoDocumento -> eliminar() -> save();

            $tables = 'dataTableBuilder';

            // Lista de tablas que se van a recargar automáticamente
            $message = sprintf('<i class="fa fa-fw fa-warning"></i> Tipo de documento <b>%s</b> eliminado',$tipoDocumento -> getCodigo());

            return $this -> responseWarningJSON($message,'danger',$tables);
        } catch(Exception $error) {
            return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el Tipo de documento. Error ' . $error->getMessage() ]);
        }
    }

}