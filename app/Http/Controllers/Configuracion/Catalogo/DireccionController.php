<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerDireccionRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\DireccionesDataTable;

/* Models */
use App\Model\Catalogo\MDireccion;

class DireccionController extends BaseController {
	
	private $form_id = 'form-direccion';
	
	public function index(DireccionesDataTable $dataTables){

		$data['table']    = $dataTables;
		$data['form_id']  = $this->form_id;
		$data['form_url'] = url('configuracion/catalogos/direcciones/nuevo');

		return view('Configuracion.Catalogo.Direccion.indexDireccion')->with($data);
	}

	public function manager(ManagerDireccionRequest $request){

		$action = Input::get('action');

		switch ($action) {
			case 1: // Nuevo
				$response = $this->nuevaDireccion();
				break;
			case 2: // Editar
				$response = $this->editarDireccion();
				break;
			case 3: // Activar / Desactivar
				$response = $this->activarDireccion();
				break;
			case 4: // Eliminar
				$response = $this->eliminarDireccion();
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(DireccionesDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formNuevaDireccion(){
		try{
	 		$data = [
	 			'title'         =>'Nueva direccion',
	 			'form_id'       => $this->form_id,
	 			'url_send_form' => url('configuracion/catalogos/direcciones/manager'),
	 			'action'        => 1,
	 			'modelo'        => null,
	 			'id'            => null,
		 	];

		 	return view('Configuracion.Catalogo.Direccion.formDireccion')->with($data);

		}catch(Exception $error){

		}
	}

	public function formEditarDireccion(){
		try{

			$data = [
	 			'title'         =>'Editar direccion',
	 			'form_id'       => $this->form_id,
	 			'url_send_form' => url('configuracion/catalogos/direcciones/manager'),
	 			'action'        => 2,
	 			'modelo'        => MDireccion::find( Input::get('id') ),
	 			'id'            => Input::get('id'),
		 	];
			
			return view('configuracion.Catalogo.Direccion.formDireccion')-> with ($data);

		}catch(Exception $error){

		}
	}


	public function nuevaDireccion(){
		try{

			$direccion = new MDireccion;
			$direccion->DIRE_NOMBRE      = Input::get('nombre');
			$direccion->DIRE_CREATED_AT  = Carbon::now();
			$direccion->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = ['dataTableBuilder',null,true];

			return response()->json(['status'=>true,'message'=>'La dirección se creó correctamente','tables'=>$tables]);
		
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al crear la dirección. Error ' . $error->getMessage() ]);
		}
	}

	public function editarDireccion(){
		try{

			$direccion = MDireccion::find( Input::get('id') );
			$direccion->DIRE_NOMBRE      = Input::get('nombre');
			$direccion->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente','tables'=>$tables]);
		
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() ]);
		}
	}

	public function activarDireccion(){
		try{
			$direccion = MDireccion::find( Input::get('id') );
			
			if( $direccion->DIRE_ENABLED == 1 ){
				$direccion->DIRE_ENABLED = 0;
				$message = 'La dirección se desactivó correctamente';
			}else{
				$direccion->DIRE_ENABLED = 1;
				$message = 'La dirección se activó correctamente';
			}
			$direccion->save();

			return response()->json(['status'=>true,'message'=>$message]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	public function eliminarDireccion(){
		try{
			$direccion = MDireccion::find( Input::get('id') );
			
			$direccion->DIRE_DELETED    = 1;
			$direccion->DIRE_DELETED_AT = Carbon::now();
			$direccion->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'La dirección se eliminó correctamente','tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar la dirección. Error ' . $error->getMessage() ]);
		}

	}

}
