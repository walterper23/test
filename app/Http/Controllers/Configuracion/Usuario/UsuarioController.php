<?php
namespace App\Http\Controllers\Configuracion\Usuario;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerUsuarioRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\UsuariosDataTable;

/* Models */
use App\Model\MUsuario;
use App\Model\MUsuarioDetalle;

class UsuarioController extends BaseController {

	private $form_id = 'form-usuario';

	public function index(UsuariosDataTable $dataTables){

		$data['table']    = $dataTables;
		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/usuarios/nuevo');

		return view('Configuracion.Usuario.indexUsuario') -> with($data);
	}

	public function manager(ManagerUsuarioRequest $request){

		switch ($request -> action) {
			case 1: // Nuevo
				$response = $this -> nuevoUsuario( $request );
				break;
			case 2: // Editar
				$response = $this -> editarUsuario( $request );
				break;
			case 3: // Activar / Desactivar
				$response = $this -> activarUsuario( $request );
				break;
			case 4: // Eliminar
				$response = $this -> eliminarUsuario( $request );
				break;
			case 5: // Cambiar contraseña
				$response = $this -> modificarPassword( $request );
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}

		return $response;

	}

	public function postDataTable(UsuariosDataTable $dataTables){
		return $dataTables -> getData();
	}

	public function verUsuario( $id_usuario ){

		$usuario = MUsuario::find( $id_usuario );

		$data['usuario'] = $usuario;

		return view('Configuracion.Usuario.verUsuario') -> with($data);
	}


	public function formUsuario(){

		$data['form_id']       = $this->form_id;
		$data['url_send_form'] = url('configuracion/usuarios/manager');

		return view('Configuracion.Usuario.formUsuario') -> with($data);
	}

	public function nuevoUsuario( $request ){

		try{

			DB::beginTransaction();

			$usuario = new MUsuario;
			$usuario -> USUA_USERNAME = $request -> usuario;
			$usuario -> USUA_PASSWORD = bcrypt( $request -> password );
			$usuario -> USUA_NOMBRE   = $request -> descripcion;
			$usuario -> USUA_AVATAR_SMALL = 'no-profile-male.png';
			$usuario -> USUA_AVATAR_FULL  = 'no-profile-male.png';
			$usuario -> save();
			
			$detalle = new MUsuarioDetalle;
			$detalle -> USDE_USUARIO   = $usuario -> getKey();
			$detalle -> USDE_NOMBRES   = $request -> nombres;
			$detalle -> USDE_APELLIDOS = $request -> apellidos;
			$detalle -> USDE_EMAIL     = $request -> email;


			$detalle -> save();

			DB::commit();

		}catch(Exception $error){
			DB::rollback();
		}


	}



	public function activarUsuario( $request ){
		try{
			$anexo = MUsuario::find( $request -> id );
			
			if( $anexo -> USUA_ENABLED == 1 ){
				$anexo -> USUA_ENABLED = 0;
				$type = 'warning';
				$message = '<i class="fa fa-warning"></i> Usuario desactivado';
			}else{
				$anexo -> USUA_ENABLED = 1;
				$type = 'info';
				$message = '<i class="fa fa-check"></i> Usuario activado';
			}
			$anexo->save();

			return response() -> json(['status'=>true,'type'=>$type,'message'=>$message]);
		}catch(Exception $error){
			return response() -> json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

}
