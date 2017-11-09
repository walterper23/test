<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\BaseController;
use Validator;

use App\Model\Catalogo\MTipoDocumento;

use DataTable;

class DepartamentoController extends BaseController{

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

		return DataTable::collection($data);
						

	}

	/**
	 * Description
	 * @return type
	 */
	public function formDepartamento(){
		try{

			$data['title'] = 'Nuevo departamento';
			
			$data['form_id'] = 'form-departamento';
			$data['url_send_form'] = url('configuracion/catalogos/departamentos/post-nuevo');
			
			$data['model'] = new MDepartamento;

			return view('Configuracion.Catalogo.Departamento.formDepartamento')->with($data);
		}catch(Exception $error){

		}
	}

	public function postNuevoDepartamento(){
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

	public function postEditarDepartamento(){
		try{


		}catch(Exception $error){
			
		}
	}

	public function eliminarDepartamento( $id ){
		try{
			$tipoDocumento = MTipoDocumento::findOrFail( $id )->where('TIDO_ENABLED',1)->where('TIDO_DELETED',0)->limit(1)->first();


		}catch(Exception $error){

		}


	}

}
