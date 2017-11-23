<?php

namespace App\Http\Controllers\Configuracion\Usuario;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\RolesDataTable;

/* Models */
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MPuesto;

class RolController extends BaseController{


	public function index(RolesDataTable $dataTables){

		$data['table'] = $dataTables;

		return view('Configuracion.Usuario.indexRol')->with($data);
	}


	public function formUsuario(){
		return view('Configuracion.Usuario.formUsuario');
	}

}
