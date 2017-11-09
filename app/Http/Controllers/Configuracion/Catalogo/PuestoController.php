<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\BaseController;
use Validator;

use App\Model\Catalogo\MTipoDocumento;

use DataTable;

class PuestoController extends BaseController{

	public function index(){

		$data['table'] = Datatable::table()
					    ->addColumn('#','Nombre','Fecha','Validar','Opciones')       
					    ->setUrl( url('configuracion/catalogos/tipos-documentos/post-data') )
					    ->noScript();


		return view('Configuracion.Catalogo.TipoDocumento.indexTipoDocumento')->with($data);
	}

	public function manager(Request $request){

	}

	public function postDataTable(){

		$data = MTipoDocumento::where('TIDO_DELETED',0)->get();

		return DataTable::collection data;						

	}

	/**
	 * Description
	 * @return type
	 */
	public function formPuesto(){
		try{

			$data['Puesto'] = 'Nuevo Puesto';
			
			$data['form-puestp'] = 'form-nuevo-tipo-documento';
			$data['url_send_form'] = url('configuracion/catalogos/puesto/post-nuevo');
			
			$data['model'] = new MPuesto;

			return view('Configuracion.Catalogo.TipoDocumento.formTipoDocumento')->with($data);
		}catch(Exception $error){

		}
	}

	public function postNuevoPuesto(){
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

	public function postEditarPuesto(){
		try{


		}catch(Exception $error){
			
		}
	}

	public function eliminarPuesto( $id ){
		try{
			$tipoDocumento = MTipoDocumento::findOrFail( $id )->where('TIDO_ENABLED',1)->where('TIDO_DELETED',0)->limit(1)->first();


		}catch(Exception $error){

		}


	}

}
