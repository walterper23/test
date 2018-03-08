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

class AnexoController extends BaseController {

	private $form_id = 'form-anexo';

	public function index(AnexosDataTable $dataTables){

		$data['table']    = $dataTables;
		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/catalogos/anexos/nuevo');

		return view('Configuracion.Catalogo.Anexo.indexAnexo')->with($data);
	}

	public function manager(ManagerAnexoRequest $request){

		switch ($request -> action) {
			case 1: // Nuevo
				$response = $this -> nuevoAnexo( $request );
				break;
			case 2: // Editar
				$response = $this -> editarAnexo( $request );
				break;
			case 3: // Activar / Desactivar
				$response = $this -> activarAnexo( $request );
				break;
			case 4: // Eliminar
				$response = $this -> eliminarAnexo( $request );
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
			$data['form_id']       = $this -> form_id;
			$data['modelo']		   = null;
			$data['action']		   = 1;
			$data['id']		       = null;

			return view('Configuracion.Catalogo.Anexo.formAnexo')->with($data);

		}catch(Exception $error){

		}
	}

	public function nuevoAnexo( $request ){
		try{

			$anexo = new MAnexo;
			$anexo -> ANEX_NOMBRE = $request -> nombre;
			$anexo -> save();			

			$tables = ['dataTableBuilder',null,true];

			return response()->json(['status'=>true,'message'=>'Nuevo anexo creado','tables' =>$tables]);
		
		}catch(Exception $error){

		}
	}

	public function formEditarAnexo(){
		try{
			$data['title']         = 'Editar anexo';
			$data['url_send_form'] = url('configuracion/catalogos/anexos/manager');
			$data['form_id']       = $this -> form_id;
			$data['modelo']		   = MAnexo::find( Input::get('id') );
			$data['action']		   = 2;
			$data['id']		       = Input::get('id');

			return view('Configuracion.Catalogo.Anexo.formAnexo')->with($data);

		}catch(Exception $error){

		}
	}

	public function editarAnexo( $request ){
		try{
			$anexo = MAnexo::find( $request -> id ) -> where('ANEX_DELETED',0) -> first();
			$anexo -> ANEX_NOMBRE = $request -> nombre;
			$anexo -> save();			

			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'Cambios guardados correctamente','tables' =>$tables]);

		}catch(Exception $error){
			
		}
	}

	public function activarAnexo( $request ){
		try{
			$anexo = MAnexo::find( $request -> id );
			
			if( $anexo -> cambiarDisponibilidad() -> disponible() ){
				$message = 'El anexo ha sido activado';
			}else{
				$message = 'El anexo ha sido desactivado';
			}
			$anexo -> save();

			return response()->json(['status'=>true,'message'=>$message]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	public function eliminarAnexo( $request ){
		try{
			$anexo = MAnexo::find( $request -> id );
			
			$anexo -> ANEX_DELETED    = 1;
			$anexo -> ANEX_DELETED_AT = Carbon::now();
			$anexo -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'Anexo eliminado correctamente','tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el anexo. Error ' . $error->getMessage() ]);
		}
	}

}