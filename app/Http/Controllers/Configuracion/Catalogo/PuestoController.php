<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerPuestoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\PuestosDataTable;

/* Models */
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MPuesto;

class PuestoController extends BaseController {

	private $form_id;

	public function __construct(){
		$this->form_id = 'form-puesto';
	}

	public function index(PuestosDataTable $dataTables){

		$data = [
			'table'    => $dataTables,
			'form_id'  => $this->form_id,
			'form_url' => url('configuracion/catalogos/puestos/nuevo'),
		];

		return view('Configuracion.Catalogo.Puesto.indexPuesto')->with($data);
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

	public function formNuevoPuesto(){
		try{

			$data = [
				'title'         => 'Nuevo puesto',
				'url_send_form' => url('configuracion/catalogos/puestos/manager'),
				'form_id'       => $this->form_id,
				'modelo'        => null,
				'action'        => 1,
				'id'            => null
			];

			$direcciones = MDireccion::with('departamentos')
									->select('DIRE_DIRECCION','DIRE_NOMBRE')
									->where('DIRE_ENABLED',1)
									->orderBy('DIRE_NOMBRE')
									->get();

			$data['direcciones'] = $direcciones->pluck('DIRE_NOMBRE','DIRE_DIRECCION')->toArray();

			$data['departamentos'] = [];

			foreach ($direcciones as $direccion) {

				$nombre_direccion = $direccion->DIRE_NOMBRE;
				foreach($direccion->departamentos as $departamento){
					$id_departamento      = $departamento->DEPA_DEPARTAMENTO;
					$nombre_departamento  = $departamento->DEPA_NOMBRE;
					$data['departamentos'][ $nombre_direccion ][ $id_departamento ] = $nombre_departamento;
				}

			}

			$data['departamentos'][0] = '- Ninguno -';


			return view('Configuracion.Catalogo.Puesto.formPuesto')->with($data);

		}catch(Exception $error){

		}
	}

	public function nuevoPuesto(){
		try{
			
			$departamento = Input::get('departamento');

			if( $departamento == 0 )
				$departamento = null;

			$puesto = new MPuesto;
			$puesto->PUES_NOMBRE       = Input::get('nombre');
			$puesto->PUES_DIRECCION    = Input::get('direccion');
			$puesto->PUES_DEPARTAMENTO = $departamento;
			$puesto->save();

			$tables = ['dataTableBuilder',null,true];

			return response()->json(['status'=>true, 'message'=>'<i class="fa fa-check"></i> El puesto se creó correctamente','tables'=>$tables]);
		}catch(Exception $error){

		}
	}

	public function formEditarPuesto(){
		try{

			$data = [
				'title'         => 'Editar puesto',
				'url_send_form' => url('configuracion/catalogos/puestos/manager'),
				'form_id'       => $this->form_id,
				'modelo'        => MPuesto::find( Input::get('id') ),
				'action'        => 2,
				'id'            => Input::get('id')
			];

			$direcciones = MDireccion::with('departamentos')
									->select('DIRE_DIRECCION','DIRE_NOMBRE')
									->where('DIRE_ENABLED',1)
									->orderBy('DIRE_NOMBRE')
									->get();

			$data['departamentos'] = [];

			foreach ($direcciones as $direccion) {

				$nombre_direccion = $direccion->DIRE_NOMBRE;
				foreach($direccion->departamentos as $departamento){
					$id_departamento      = $departamento->DEPA_DEPARTAMENTO;
					$nombre_departamento  = $departamento->DEPA_NOMBRE;
					$data['departamentos'][ $nombre_direccion ][ $id_departamento ] = $nombre_departamento;
				}

			}

			return view('Configuracion.Catalogo.Puesto.formPuesto')->with($data);

		}catch(Exception $error){

		}
	}

	public function editarPuesto(){
		try{
			$puesto = MPuesto::find( Input::get('id') );
			$puesto->PUES_NOMBRE       = Input::get('nombre');
			$puesto->PUES_DEPARTAMENTO = Input::get('departamento');
			$puesto->save();

			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true, 'message'=>'<i class="fa fa-check"></i> Los cambios se guardaron correctamente','tables'=>$tables]);
		}catch(Exception $error){

		}
	}

	public function activarPuesto(){
		try{
			$puesto = MPuesto::find( Input::get('id') );
			
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
			$puesto = MPuesto::find( Input::get('id') );
			
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
