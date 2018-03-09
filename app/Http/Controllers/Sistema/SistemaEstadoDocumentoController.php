<?php
namespace App\Http\Controllers\Configuracion\Sistema;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerSistemaEstadoDocumentoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\SistemaEstadosDocumentosDataTable;

/* Models */
use App\Model\Sistema\MSistemaEstadoDocumento;

class SistemaEstadoDocumentoController extends BaseController {

	private $form_id;

	public function __construct(){
		$this -> form_id = 'form-estado-documento';
	}

	public function index(SistemaEstadosDocumentosDataTable $dataTables){

		$data['table']    = $dataTables;
		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/sistema/estados-documentos/nuevo');

		return view('Configuracion.Sistema.EstadoDocumento.indexEstadoDocumento') -> with($data);
	}

	public function manager(ManagerestadoDocumentoRequest $request){

		switch ($request -> action) {
			case 2: // Editar
				$response = $this -> editarestadoDocumento( $request );
				break;
			case 3: // Activar / Desactivar
				$response = $this -> activarestadoDocumento( $request );
				break;
			case 5: // Validar estado de documento
				$response = $this -> validarestadoDocumento( $request );
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(SistemaEstadosDocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formEditarestadoDocumento(){
		try{

			$data['title']         = 'Editar estado de documento';
			$data['form_id']       = $this -> form_id;
			$data['url_send_form'] = url('configuracion/sistema/estados-documentos/manager');
			$data['action']        = 2;
			$data['model']         = MSistemaestadoDocumento::find( Input::get('id') );
			$data['id']            = Input::get('id');

			return view('Configuracion.Sistema.estadoDocumento.formestadoDocumento') -> with($data);
		}catch(Exception $error){

		}
	}

	public function editarestadoDocumento( $request ){
		try{

			$estadoDocumento = MSistemaestadoDocumento::findOrFail( $request -> id );
			$estadoDocumento -> SYTD_NOMBRE_estado = $request -> nombre;
			$estadoDocumento -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-check"></i> estado de documento <b>%s</b> modificado',$estadoDocumento -> getCodigo());
			return $this -> responseSuccessJSON($message,$tables);

		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al editar el estado de documento. Error ' . $error->getMessage() ]);
		}
	}

	public function activarestadoDocumento( $request ){
		try{
			$estadoDocumento = MSistemaestadoDocumento::find( $request -> id );
			
			$estadoDocumento -> cambiarDisponibilidad() -> save();

			if( $estadoDocumento -> disponible() ){
				$message = sprintf('<i class="fa fa-fw fa-check"></i> estado de documento <b>%s</b> activado',$estadoDocumento -> getCodigo());
				return $this -> responseInfoJSON($message);
			}else{
				$message = sprintf('<i class="fa fa-fw fa-warning"></i> estado de documento <b>%s</b> desactivado',$estadoDocumento -> getCodigo());
				return $this -> responseWarningJSON($message);
			}

		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	public function validarestadoDocumento(){
		try{
			$estadoDocumento = MSistemaestadoDocumento::find( $request -> id );
			
			$estadoDocumento -> SYTD_VALIDAR = $estadoDocumento -> SYTD_VALIDAR * -1 + 1;
			$estadoDocumento -> save();

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente']);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() ]);
		}


	}

}