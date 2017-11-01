<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\BaseController;

use App\Model\Catalogo\MTipoDocumento;

use DataTable;

class TipoDocumentoController extends BaseController{

	public function index(){

		return view('Catalogo.TipoDocumento.indexTipoDocumento');
	}

	public function manager(Request $request){

	}

	public function postDataTable(){

		$data = MTipoDocumento::where('TIDO_DELETED',0)->get();

		return DataTable::collection($data)
						->showColumns('TIDO_TIPO_DOCUMENTO','TIDO_NOMBRE_TIPO','TIDO_CREATED_AT')
						->searchColumns('TIDO_NOMBRE_TIPO')
						->make();

	}

	/**
	 * Description
	 * @return type
	 */
	public function formularioTipoDocumento(){
		try{

			$data['model'] = new MTipoDocumento;


			return view()->with($data);
		}catch(Exception $error){

		}
	}

	public function nuevoTipoDocumento(){
		try{


		}catch(Exception $error){

		}
	}

	public function modificarTipoDocumento(){
		try{


		}catch(Exception $error){
			
		}
	}

	public function eliminarTipoDocumento( $id ){
		try{
			$tipoDocumento = MTipoDocumento::findOrFail( $id )->where('TIDO_ENABLED',1)->where('TIDO_DELETED',0)->limit(1)->first();


		}catch(Exception $error){

		}


	}

}
