<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\DataTables\CustomDataTablesController;

use App\Model\Catalogo\MAnexo;
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MPuesto;
use App\Model\Catalogo\MTipoDocumento;


class CatalogoManagerController extends BaseController{

	public function index(){

		$data = [
			'anexos'           => MAnexo::where('ANEX_DELETED',0)->count(),
			'departamentos'    => MDepartamento::where('DEPA_DELETED',0)->count(),
			'direcciones'      => MDireccion::where('DIRE_DELETED',0)->count(),
			'puestos'          => MPuesto::where('PUES_DELETED',0)->count(),
			'tipos_documentos' => MTipoDocumento::where('TIDO_DELETED',0)->count()
		];

		return view('Configuracion.Catalogo.index')->with($data);

	}


}
