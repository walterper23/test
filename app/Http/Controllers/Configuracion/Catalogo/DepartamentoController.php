<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerDepartamentoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\DepartamentosDataTable;

/* Models */
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MDepartamento;

class DepartamentoController extends BaseController{
	private $form_id;
	public function __construct(){
$this->form_id='form-departamento';
	}
	
	public function index(DepartamentosDataTable $dataTables){
		$data['table'] = $dataTables;
		$data['form_id']= $this ->form_id;
		$data['form_url']= url('configuracion/catalogos/departamentos/nuevo');


    	return view('Configuracion.Catalogo.Departamento.indexDepartamento')->with($data);
	}

	public function manager(ManagerDepartamentoRequest $request){

		$action = Input::get('action');

		switch ($action) {
			case 1: // Nuevo
				$response = $this->nuevoDepartamento();
				break;
			case 2: // Editar
				$response = $this->editarDepartamento();
				break;
			case 3: // Activar / Desactivar
				$response = $this->activarDepartamento();
				break;
			case 4: // Eliminar
				$response = $this->eliminarDepartamento();
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(DepartamentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formDepartamento(){
		try{

			$data =[];
			$data['title']= 'Nuevo Departamento';
			$data['url_send_form']= url('configuracion/catalogos/departamentos/manager');
			$data['form_id']= $this->form_id;
			$data['modelo']= null;
			$data['action']= 1;

			$data['direcciones'] = [''=>'Seleccione una opción'] + MDireccion::select('DIRE_DIRECCION','DIRE_NOMBRE')->pluck('DIRE_NOMBRE','DIRE_DIRECCION')->toArray();

			return view('Configuracion.Catalogo.Departamento.formDepartamento')->with($data);

		}catch(Exception $error){

		}
	}



	public function nuevoDepartamento(){
		try{
		$departamento = new MDepartamento;
		$departamento->DEPA_NOMBRE = Input::get('nombre');
		$departamento->DEPA_DIRECCION = Input::get('direccion');
		$departamento->save();

		$tables = ['dataTableBuilder',null,true];

		return response()->json(['status'=>true, 'message'=>'El departamento se creó correctamente','tables'=>$tables]);
				}catch(Exception $error){

		}
	}

	public function editarDepartamento(){
		try{

		$departamento = MDepartamento::find( Input::get('id'))->where('PUES_DELETED',0);
		$departamento->DEPA_NOMBRE = Input::get('nombre');
		$departamento->DEPA_DIRECCION = Input::get('direccion');
		$departamento->save();

		$tables = 'dataTableBuilder';

		return response()->json(['status'=>true, 'message'=>'Los cambios se guardaron correctamente','tables'=>$tables]);
				}catch(Exception $error){

		}catch(Exception $error){
			
		}
	}

	public function activarDepartamento(){
		try{
			$departamento = MDepartamento::where('DEPA_DEPARTAMENTO',Input::get('id') )
								->where('DEPA_DELETED',0)->limit(1)->first();
			
			if( $departamento->DEPA_ENABLED == 1 ){
				$departamento->DEPA_ENABLED = 0;
				$message = 'El departamento se desactivó correctamente';
			}else{
				$departamento->DEPA_ENABLED = 1;
				$message = 'El departamento se activó correctamente';
			}
			$departamento->save();

			return response()->json(['status'=>true,'message'=>$message]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}


	public function eliminarDepartamento(){
		try{
			$departamento = MDepartamento::where('DEPA_DEPARTAMENTO',Input::get('id') )
								->where('DEPA_DELETED',0)->limit(1)->first();
			
			
			$departamento->DEPA_DELETED    = 1;
			$departamento->DEPA_DELETED_AT = Carbon::now();
			$departamento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'El departamento se eliminó correctamente','tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el anexo. Error ' . $error->getMessage() ]);
		}
	}

}
