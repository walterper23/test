<?php
namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\BaseController;

use App\Model\Catalogo\MTipoDocumento;

use Datatables;

class CatalogoManagerController extends BaseController{


	public function index(){

		return view('Administrador.Catalogos.index');

	}

	public function postData(){
		return Datatables::of(MTipoDocumento::all())->setRowClass(function($user){
                return 'text-center';
            })->make(true);
	}

}
