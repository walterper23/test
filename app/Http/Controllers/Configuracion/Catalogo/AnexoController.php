<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\BaseController;
use Validator;

use App\Model\Catalogo\MTipoDocumento;

use DataTable;

class AnexoController extends BaseController{

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

		return DataTable::collection($data)
						->showColumns('TIDO_TIPO_DOCUMENTO','TIDO_NOMBRE_TIPO','TIDO_CREATED_AT')
						->addColumn('Validar',function($query){

							if($query->TIDO_VALIDAR) return '<span class="badge badge-success">Validar</span>';
							return '<span class="badge badge-info">No validar</span>';


						})->addColumn('Opciones',function($query){

								$buttons = '';
							
								$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-warning" onclick="hTipoDocumento.disable('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-level-down"></i> Desactivar</button>';


								$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-trash"></i> Eliminar</button>';
								
								$buttons .= '<input type="checkbox" value="'.$query->TIDO_TIPO_DOCUMENTO.'"> Marcar';

								return $buttons;

						})
						->searchColumns('TIDO_TIPO_DOCUMENTO','TIDO_NOMBRE_TIPO','TIDO_CREATED_AT')
						->make();

	}

	/**
	 * Description
	 * @return type
	 */
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
