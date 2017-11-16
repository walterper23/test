<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\DataTables\CustomDataTablesController;

use App\Model\Catalogo\MTipoDocumento;
use App\Model\MDocumento;


class CatalogoManagerController extends BaseController{

	public function index(){

		return view('Configuracion.Catalogo.index');

	}


}
