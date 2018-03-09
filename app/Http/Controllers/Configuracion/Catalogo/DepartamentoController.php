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

class DepartamentoController extends BaseController {
	
	private $form_id = 'form-departamento';

	public function index(DepartamentosDataTable $dataTables){
		
		$data['table']    = $dataTables;
		$data['form_id']  = $this ->form_id;
		$data['form_url'] = url('configuracion/catalogos/departamentos/nuevo');

    	return view('Configuracion.Catalogo.Departamento.indexDepartamento')->with($data);
	}

	public function manager(ManagerDepartamentoRequest $request){

		switch ($request -> action) {
			case 1: // Nuevo
				$response = $this -> nuevoDepartamento( $request );
				break;
			case 2: // Editar
				$response = $this -> editarDepartamento( $request );
				break;
			case 3: // Activar / Desactivar
				$response = $this -> activarDepartamento( $request );
				break;
			case 4: // Eliminar
				$response = $this -> eliminarDepartamento( $request );
				break;
			default:
				return response() -> json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(DepartamentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formNuevoDepartamento(){
		try{

			$data                  = [];
			$data['title']         = 'Nuevo Departamento';
			$data['url_send_form'] = url('configuracion/catalogos/departamentos/manager');
			$data['form_id']       = $this -> form_id;
			$data['modelo']        = null;
			$data['action']        = 1;
			$data['id']            = Input::get('id');

			$data['direcciones'] = MDireccion::select('DIRE_DIRECCION','DIRE_NOMBRE')->pluck('DIRE_NOMBRE','DIRE_DIRECCION')->toArray();

			return view('Configuracion.Catalogo.Departamento.formDepartamento')->with($data);

		}catch(Exception $error){

		}
	}

	public function nuevoDepartamento( $request ){
		try{
			$departamento = new MDepartamento;
			$departamento -> DEPA_NOMBRE    = $request -> nombre;
			$departamento -> DEPA_DIRECCION = $request -> direccion;
			$departamento -> save();

			$tables = ['dataTableBuilder',null,true];

			return response() -> json(['status'=>true, 'message'=>'El departamento se creó correctamente','tables'=>$tables]);
		}catch(Exception $error){

		}
	}

	public function formEditarDepartamento(){
		try{

			$data = [];
			$data['title']         = 'Editar departamento';
			$data['url_send_form'] = url('configuracion/catalogos/departamentos/manager');
			$data['form_id']       = $this -> form_id;
			$data['modelo']        = MDepartamento::find( Input::get('id') );
			$data['action']        = 2;
			$data['id']            = Input::get('id');

			$data['direcciones'] = MDireccion::select('DIRE_DIRECCION','DIRE_NOMBRE')
											->orderBy('DIRE_NOMBRE')
											->pluck('DIRE_NOMBRE','DIRE_DIRECCION')
											->toArray();

			return view('Configuracion.Catalogo.Departamento.formDepartamento')->with($data);

		}catch(Exception $error){

		}
	}

	public function editarDepartamento( $request ){
		try{
			$departamento = MDepartamento::find( $request -> id );
			$departamento -> DEPA_NOMBRE    = $request -> nombre;
			$departamento -> DEPA_DIRECCION = $request -> direccion;
			$departamento -> save();

			$message = sprintf('<i class="fa fa-fw fa-check"></i> Departamento <b>%s</b> modificado',$departamento -> getCodigo());

			$tables = 'dataTableBuilder';

			return $this -> responseSuccessJSON($message,$tables);
		}catch(Exception $error){

		}
	}

	public function activarDepartamento( $request ){
		try{
			$departamento = MDepartamento::find( $request -> id );
			
			if( $departamento -> cambiarDisponibilidad() -> disponible() ){
				$type = 'info';
				$message = sprintf('<i class="fa fa-fw fa-check"></i> Departamento <b>%s</b> activado',$departamento -> getCodigo());
			}else{
				$type = 'warning';
				$message = sprintf('<i class="fa fa-fw fa-warning"></i> Departamento <b>%s</b> desactivado',$departamento -> getCodigo());
			}

			$departamento -> save();

			return $this -> responseSuccessJSON($message,$type);
		}catch(Exception $error){
			return response() -> json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}


	public function eliminarDepartamento( $request ){
		try{
			$departamento = MDepartamento::find( $request -> id );
			
			$departamento -> eliminar() -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-warning"></i> Departamento <b>%s</b> eliminado',$departamento -> getCodigo());

			return $this -> responseWarningJSON($message,'danger',$tables);
		}catch(Exception $error){
			return response() -> json(['status'=>false,'message'=>'Ocurrió un error al eliminar el anexo. Error ' . $error->getMessage() ]);
		}
	}

}
