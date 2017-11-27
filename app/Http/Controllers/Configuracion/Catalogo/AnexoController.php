<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerAnexoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\AnexosDataTable;

/* Models */
use App\Model\Catalogo\MAnexo;

class AnexoController extends BaseController{

	private $form_id;

	public function __construct(){
		$this->form_id = 'form-anexo';
	}

	public function index(AnexosDataTable $dataTables){

		$data['table']    = $dataTables;
		$data['form_id']  = $this->form_id;
		$data['form_url'] = url('configuracion/catalogos/anexos/nuevo');

		return view('Configuracion.Catalogo.Anexo.indexAnexo')->with($data);
	}

	public function manager(ManagerAnexoRequest $request){

		$action = Input::get('action');

		switch ($action) {
			case 1: // Nuevo
				$response = $this->nuevoAnexo();
				break;
			case 2: // Editar
				$response = $this->editarAnexo();
				break;
			case 3: // Activar / Desactivar
				$response = $this->activarAnexo();
				break;
			case 4: // Eliminar
				$response = $this->eliminarAnexo();
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(AnexosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formNuevoAnexo(){
		try{

			$data = [];

			$data['title']         = 'Nuevo anexo';
			$data['url_send_form'] = url('configuracion/catalogos/anexos/manager');
			$data['form_id']       = $this->form_id;
			$data['modelo']		   = null;
			$data['action']		   = 1;
			$data['id']		       = null;

			return view('Configuracion.Catalogo.Anexo.formAnexo')->with($data);

		}catch(Exception $error){

		}
	}

	public function nuevoAnexo(){
		try{

			$anexo = new MAnexo;
			$anexo->ANEX_NOMBRE = Input::get('nombre');
			$anexo->save();			

			$tables = ['dataTableBuilder',null,true];

			return response()->json(['status'=>true,'message'=>'El anexo se creó correctamente','tables' =>$tables]);
		
		}catch(Exception $error){

		}
	}

	public function formEditarAnexo(){
		try{
			$data['title']         = 'Editar anexo';
			$data['url_send_form'] = url('configuracion/catalogos/anexos/manager');
			$data['form_id']       = $this->form_id;
			$data['modelo']		   = MAnexo::find( Input::get('id') )->where('ANEX_DELETED',0)->first();
			$data['action']		   = 2;
			$data['id']		       = Input::get('id');

			return view('Configuracion.Catalogo.Anexo.formAnexo')->with($data);

		}catch(Exception $error){

		}
	}

	public function editarAnexo(){
		try{
			$anexo = MAnexo::find( Input::get('id') )->where('ANEX_DELETED',0)->first();
			$anexo->ANEX_NOMBRE = Input::get('nombre');
			$anexo->save();			

			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente','tables' =>$tables]);

		}catch(Exception $error){
			
		}
	}

	public function activarAnexo(){
		try{
			$anexo = MAnexo::where('ANEX_ANEXO',Input::get('id') )
								->where('ANEX_DELETED',0)->limit(1)->first();
			
			if( $anexo->ANEX_ENABLED == 1 ){
				$anexo->ANEX_ENABLED = 0;
				$message = 'El anexo se desactivó correctamente';
			}else{
				$anexo->ANEX_ENABLED = 1;
				$message = 'El anexo se activó correctamente';
			}
			$anexo->save();

			return response()->json(['status'=>true,'message'=>$message]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}


	public function eliminarAnexo(){
		try{
			$anexo = MAnexo::where('ANEX_ANEXO',Input::get('id') )
								->where('ANEX_DELETED',0)->limit(1)->first();
			
			$anexo->ANEX_DELETED    = 1;
			$anexo->ANEX_DELETED_AT = Carbon::now();
			$anexo->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'El anexo se eliminó correctamente','tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el anexo. Error ' . $error->getMessage() ]);
		}
	}

}