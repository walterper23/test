<?php

namespace App\Http\Controllers\Configuracion\Usuario;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\UsuariosDataTable;

/* Models */
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MPuesto;


class UsuarioController extends BaseController{


	public function index(UsuariosDataTable $dataTables){

		$data['table'] = $dataTables;

		return view('Configuracion.Usuario.indexUsuario')->with($data);
	}

	public function postDataTable(UsuariosDataTable $dataTables){
		return $dataTables->getData();
	}


	public function formUsuario(){
		return view('Configuracion.Usuario.formUsuario');
	}

}
