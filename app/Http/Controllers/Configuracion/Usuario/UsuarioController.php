<?php

namespace App\Http\Controllers\Configuracion\Usuario;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerUsuarioRequest;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\UsuariosDataTable;

/* Models */
use App\Model\MUsuario;


class UsuarioController extends BaseController{


	public function index(UsuariosDataTable $dataTables){

		$data['table'] = $dataTables;

		return view('Configuracion.Usuario.indexUsuario')->with($data);
	}

	public function manager(ManagerUsuarioRequest $request){

		$action = Input::get('action');

		switch ($action) {
			case 1:

				break;
			case 2:

				break;
			case 3: // Activar / Desactivar
				$response = $this->activarUsuario();
				break;
			case 4:

				break;
			
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}

		return $response;

	}

	public function postDataTable(UsuariosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function verUsuario( $id_usuario ){

		$usuario = MUsuario::find( $id_usuario );

		$data['usuario'] = $usuario;

		return view('Configuracion.Usuario.verUsuario')->with($data);
	}


	public function formUsuario(){
		return view('Configuracion.Usuario.formUsuario');
	}

	public function activarUsuario(){
		try{
			$anexo = MUsuario::find( Input::get('id') );
			
			if( $anexo->USUA_ENABLED == 1 ){
				$anexo->USUA_ENABLED = 0;
				$type = 'warning';
				$message = '<i class="fa fa-warning"></i> Usuario desactivado';
			}else{
				$anexo->USUA_ENABLED = 1;
				$type = 'info';
				$message = '<i class="fa fa-check"></i> Usuario activado';
			}
			$anexo->save();

			return response()->json(['status'=>true,'type'=>$type,'message'=>$message]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

}
