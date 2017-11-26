<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerPuestoRequest;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\PuestosDataTable;

/* Models */
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MPuesto;

class PuestoController extends BaseController{

	public function index(PuestosDataTable $dataTables){
		return view('Configuracion.Catalogo.Puesto.indexPuesto')->with('table', $dataTables);
	}

	public function manager(ManagerPuestoRequest $request){

		$action = Input::get('action');

		switch ($action) {
			case 1: // Nuevo
				$response = $this->nuevoPuesto();
				break;
			case 2: // Editar
				$response = $this->editarPuesto();
				break;
			case 3: // Activar / Desactivar
				$response = $this->activarPuesto();
				break;
			case 4: // Eliminar
				$response = $this->eliminarPuesto();
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(PuestosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formPuesto(){
		try{

			$data = [
				'title'         => 'Nuevo puesto',
				'url_send_form' => '',
				'form_id'       => '',
			];

			$data['departamentos'] = MDepartamento::select('DEPA_DEPARTAMENTO','DEPA_NOMBRE')
									->pluck('DEPA_NOMBRE','DEPA_DEPARTAMENTO')
									->toArray();

			return view('Configuracion.Catalogo.Puesto.formPuesto')->with($data);

		}catch(Exception $error){

		}
	}

	public function nuevoPuesto(){
		try{
		
		}catch(Exception $error){

		}
	}

	public function editarPuesto(){
		try{

		}catch(Exception $error){
			
		}
	}

	public function activarPuesto(){
		try{
			$puesto = MPuesto::where('PUES_PUESTO',Input::get('id') )
								->where('PUES_DELETED',0)->limit(1)->first();
			
			if( $puesto->PUES_ENABLED == 1 ){
				$puesto->PUES_ENABLED = 0;
				$message = 'El puesto se desactivó correctamente';
			}else{
				$puesto->PUES_ENABLED = 1;
				$message = 'El puesto se activó correctamente';
			}
			$puesto->save();

			return response()->json(['status'=>true,'message'=>$message]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	public function eliminarPuesto(){
		try{
			$puesto = MPuesto::where('PUES_PUESTO',Input::get('id'))
								->where('PUES_DELETED',0)->limit(1)->first();
			
			$puesto->PUES_ENABLED    = 0;
			$puesto->PUES_DELETED    = 1;
			$puesto->PUES_DELETED_AT = Carbon::now();
			$puesto->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'El puesto se eliminó correctamente','tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el puesto. Error ' . $error->getMessage() ]);
		}

	}

}
