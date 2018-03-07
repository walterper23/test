<?php
namespace App\Http\Controllers\Configuracion\Sistema;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerTipoDocumentoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\SystemTiposDocumentosDataTable;

/* Models */
use App\Model\System\MSystemTipoDocumento;

class SystemTipoDocumentoController extends BaseController {

	private $form_id;

	public function __construct(){
		$this->form_id = 'form-tipo-documento';
	}

	public function index(SystemTiposDocumentosDataTable $dataTables){

		$data['table']    = $dataTables;
		$data['form_id']  = $this->form_id;
		$data['form_url'] = url('configuracion/sistema/tipos-documentos/nuevo');

		return view('Configuracion.Sistema.TipoDocumento.indexTipoDocumento')->with($data);
	}

	public function manager(ManagerTipoDocumentoRequest $request){

		$action = Input::get('action');

		switch ($action) {
			case 1: // Nuevo
				$response = $this->nuevoTipoDocumento();
				break;
			case 2: // Editar
				$response = $this->editarTipoDocumento();
				break;
			case 3: // Activar / Desactivar
				$response = $this->activarTipoDocumento();
				break;
			case 4: // Eliminar
				$response = $this->eliminarTipoDocumento();
				break;
			case 5: // Validar tipo de documento
				$response = $this->validarTipoDocumento();
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(SystemTiposDocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formNuevoTipoDocumento(){
		try{

			$data = [
				'title'         => 'Nuevo tipo de documento',
				'form_id'       => $this->form_id,
				'url_send_form' => url('configuracion/sistema/tipos-documentos/manager'),
				'action'        => 1,
				'model'         => null,
				'id'            => null
			];
			
			return view('Configuracion.Sistema.TipoDocumento.formTipoDocumento')->with($data);
		}catch(Exception $error){

		}
	}

	public function nuevoTipoDocumento(){
		try{

			$tipoDocumento = new MSystemTipoDocumento;
			$tipoDocumento->TIDO_NOMBRE_TIPO = Input::get('nombre');
			$tipoDocumento->TIDO_CREATED_AT  = Carbon::now();
			$tipoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = ['dataTableBuilder',null,true];

			return response()->json(['status'=>true,'message'=>'El tipo de documento se creó correctamente','tables'=>$tables]);
		
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al crear el tipo de documento. Error ' . $error->getMessage() ]);
		}
	}

	public function formEditarTipoDocumento(){
		try{

			$data['title'] = 'Editar tipo de documento';
			$data['form_id'] = $this->form_id;
			$data['url_send_form'] = url('configuracion/sistema/tipos-documentos/manager');
			$data['action'] = 2;
			$data['model'] = MSystemTipoDocumento::find( Input::get('id') );
			$data['id'] = Input::get('id');

			return view('Configuracion.Sistema.TipoDocumento.formTipoDocumento')->with($data);
		}catch(Exception $error){

		}
	}

	public function editarTipoDocumento(){
		try{

			$tipoDocumento = MSystemTipoDocumento::findOrFail( Input::get('id') );
			$tipoDocumento->TIDO_NOMBRE_TIPO = Input::get('nombre');
			$tipoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente','tables'=>$tables]);

		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al editar el tipo de documento. Error ' . $error->getMessage() ]);
		}
	}

	public function activarTipoDocumento(){
		try{
			$tipoDocumento = MSystemTipoDocumento::find( Input::get('id') );
			
			if( $tipoDocumento->TIDO_ENABLED == 1 ){
				$tipoDocumento->TIDO_ENABLED = 0;
				$type = 'warning';
				$message = '<i class="fa fa-warning"></i> Tipo de documento desactivado';
			}else{
				$tipoDocumento->TIDO_ENABLED = 1;
				$type = 'info';
				$message = '<i class="fa fa-check"></i> Tipo de documento activado';
			}
			$tipoDocumento->save();

			return response()->json(['status'=>true,'type'=>$type,'message'=>$message]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	public function eliminarTipoDocumento(){
		try{
			$tipoDocumento = MSystemTipoDocumento::find( Input::get('id') );
			
			$tipoDocumento->TIDO_DELETED    = 1;
			$tipoDocumento->TIDO_DELETED_AT = Carbon::now();
			$tipoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'El tipo de documento se eliminó correctamente','tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el tipo de documento. Error ' . $error->getMessage() ]);
		}

	}

	public function validarTipoDocumento(){
		try{
			$tipoDocumento = MSystemTipoDocumento::find( Input::get('id') );
			
			$tipoDocumento->TIDO_VALIDAR = $tipoDocumento->TIDO_VALIDAR * -1 + 1;
			$tipoDocumento->save();

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente']);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() ]);
		}


	}

}