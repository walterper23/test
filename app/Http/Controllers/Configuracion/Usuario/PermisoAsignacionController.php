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


class PermisoAsignacionController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this -> setLog('PermisoAsignacionController.log');
	}

	public function index(){
		return view('Configuracion.Usuario.indexPermisoAsignacion');
	}


	public function formUsuario(){
		return view('Configuracion.Usuario.formUsuario');
	}

}
