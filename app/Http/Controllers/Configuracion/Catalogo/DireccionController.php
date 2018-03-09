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
		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/catalogos/direcciones/nuevo');

		return view('Configuracion.Catalogo.Direccion.indexDireccion')->with($data);
	}

	public function manager(ManagerDireccionRequest $request){

		switch ($request -> action) {
			case 1: // Nuevo
				$response = $this -> nuevaDireccion( $request );
				break;
			case 2: // Editar
				$response = $this -> editarDireccion( $request );
				break;
			case 3: // Activar / Desactivar
				$response = $this -> activarDireccion( $request );
				break;
			case 4: // Eliminar
				$response = $this -> eliminarDireccion( $request );
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
	 			'title'         =>'Nueva dirección',
	 			'form_id'       => $this -> form_id,
	 			'url_send_form' => url('configuracion/catalogos/direcciones/manager'),
	 			'action'        => 1,
	 			'modelo'        => null,
	 			'id'            => null,
		 	];

		 	return view('Configuracion.Catalogo.Direccion.formDireccion')->with($data);

		}catch(Exception $error){

		}
	}

	public function nuevaDireccion( $request ){
		try{

			$direccion = new MDireccion;
			$direccion -> DIRE_NOMBRE      = $request -> nombre;
			$direccion -> DIRE_CREATED_AT  = Carbon::now();
			$direccion -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = ['dataTableBuilder',null,true];

			return response()->json(['status'=>true,'message'=>'La dirección se creó correctamente','tables'=>$tables]);
		
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al crear la dirección. Error ' . $error->getMessage() ]);
		}
	}

	public function formEditarDireccion(){
		try{

			$data = [
	 			'title'         =>'Editar dirección',
	 			'form_id'       => $this -> form_id,
	 			'url_send_form' => url('configuracion/catalogos/direcciones/manager'),
	 			'action'        => 2,
	 			'modelo'        => MDireccion::find( Input::get('id') ),
	 			'id'            => Input::get('id'),
		 	];
			
			return view('configuracion.Catalogo.Direccion.formDireccion')-> with ($data);

		}catch(Exception $error){

		}
	}

	public function editarDireccion( $request ){
		try{

			$direccion = MDireccion::find( $request -> id );
			$direccion -> DIRE_NOMBRE = $request -> nombre;
			$direccion -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente','tables'=>$tables]);
		
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() ]);
		}
	}

	public function activarDireccion( $request ){
		try{
			$direccion = MDireccion::find( $request -> id );

			if( $direccion -> cambiarDisponibilidad() -> disponible() ){
				$type = 'info';
				$message = sprintf('<i class="fa fa-fw fa-check"></i> Dirección <b>%s</b> activada',$direccion -> getCodigo());
			}else{
				$type = 'warning';
				$message = sprintf('<i class="fa fa-fw fa-warning"></i> Dirección <b>%s</b> desactivada',$direccion -> getCodigo());
			}

			$direccion -> save();

			return $this -> responseSuccessJSON($message,$type);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	public function eliminarDireccion( $request ){
		try{
			$direccion = MDireccion::find( $request -> id );
			
			$direccion -> eliminar() -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-warning"></i> Dirección <b>%s</b> eliminada',$direccion -> getCodigo());

			return $this -> responseWarningJSON($message,'danger',$tables);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar la dirección. Error ' . $error->getMessage() ]);
		}

	}

}