<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\TiposDocumentosDataTable;

/* Models */
use App\Model\Catalogo\MTipoDocumento;

class TipoDocumentoController extends BaseController{

	public function index(TiposDocumentosDataTable $dataTables){
		return view('Configuracion.Catalogo.TipoDocumento.indexTipoDocumento')->with('table', $dataTables);
	}

	public function postDataTable(TiposDocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formTipoDocumento(){
		try{

			$data['title'] = 'Nuevo tipo de documento';
			
			$data['form_id'] = 'form-nuevo-tipo-documento';
			$data['url_send_form'] = url('configuracion/catalogos/tipos-documentos/post-nuevo');
			
			$data['model'] = new MTipoDocumento;

			return view('Configuracion.Catalogo.TipoDocumento.formTipoDocumento')->with($data);
		}catch(Exception $error){

		}
	}

	public function postNuevoTipoDocumento(){
		try{
			$data = Input::all();

			$rules = [ 'nombre' => 'required|min:1,max:255' ];
			$messages = [
				'nombre.required' => 'Introduzca un nombre',
				'nombre.min'      => 'Mínimo :min caracter',
				'nombre.max'      => 'Máximo :max caracteres'
			];

			$validar = Validator::make($data,$rules,$messages);

			if( !$validar->passes() ){
				return response()->json(['request'=>false,'message'=>$validar->getErrors()]);
			}

			$tipoDocumento = new MTipoDocumento;
			$tipoDocumento->TIDO_NOMBRE_TIPO = $data['nombre'];
			$tipoDocumento->save();

			return response()->json(['status'=>true,'message'=>'El tipo de documento se creó correctamente']);
		
		}catch(Exception $error){

		}
	}

	public function postEditarTipoDocumento(){
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
