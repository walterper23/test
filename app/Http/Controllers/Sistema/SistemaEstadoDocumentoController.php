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

	public function manager(ManagerSistemaEstadoDocumentoRequest $request){

		switch ($request -> action) {
			case 2: // Editar
				$response = $this -> editarEstadoDocumento( $request );
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

	public function formEditarestadoDocumento(Request $request){
		try{

			$data['title']         = 'Editar estado de documento';
			$data['form_id']       = $this -> form_id;
			$data['url_send_form'] = url('configuracion/sistema/estados-documentos/manager');
			$data['action']        = 2;
			$data['model']         = MSistemaestadoDocumento::find( $request -> id );
			$data['id']            = $request -> id;

			return view('Configuracion.Sistema.EstadoDocumento.formEstadoDocumento') -> with($data);
		}catch(Exception $error){

		}
	}

	public function editarEstadoDocumento( $request ){
		try{

			$estadoDocumento = MSistemaestadoDocumento::findOrFail( $request -> id );
			$estadoDocumento -> SYED_NOMBRE = $request -> nombre;
			$estadoDocumento -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-check"></i> estado de documento <b>%s</b> modificado',$estadoDocumento -> getCodigo());

			return $this -> responseSuccessJSON($message,$tables);

		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al editar el estado de documento. Error ' . $error->getMessage() ]);
		}
	}

}