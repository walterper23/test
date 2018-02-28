<?php
namespace App\Http\Controllers\Configuracion\Usuario;

use Illuminate\Http\Request;
use App\Http\Requests;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\PermisosDataTable;

/* Models */
use App\Model\Catalogo\MTipoDocumento;
use App\Model\MDocumento;


class PermisoController extends BaseController {


	public function index(){
		return view('Configuracion.Usuario.indexPermiso');
	}


	public function formUsuario(){
		return view('Configuracion.Usuario.formUsuario');
	}

}
