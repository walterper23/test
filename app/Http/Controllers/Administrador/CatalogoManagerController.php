<?php
namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\BaseController;

use App\Model\Catalogo\MTipoDocumento;

class CatalogoManagerController extends BaseController{


	public function index(){


		$tiposDocumentos = MTipoDocumento::all();

		return view('Administrador.Catalogos.index', compact('tiposDocumentos'));

	}

}
