<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\BaseController;
use Validator;

use App\Model\Catalogo\MTipoDocumento;

use Yajra\DataTables\DataTables;

class TipoDocumentoController extends BaseController{

	public function index(DataTables $datatables){

		$columns = ['opciones','id','nombre','fecha','validar'];

		$data['table'] = $datatables->getHtmlBuilder()
							->columns($columns)
							->parameters(['saveState'=>true])
							->ajax(['method'=>'POST','url'=>url('configuracion/catalogos/tipos-documentos/post-data')]);

		return view('Configuracion.Catalogo.TipoDocumento.indexTipoDocumento')->with($data);
	}

	public function manager(Request $request){

	}

	public function postDataTable(){

		$data = MTipoDocumento::selectRaw('TIDO_TIPO_DOCUMENTO as id, TIDO_NOMBRE_TIPO as nombre, TIDO_CREATED_AT as fecha, TIDO_VALIDAR')->where('TIDO_DELETED',0);

		return DataTables::of($data)
						->addColumn('validar', function($query){
							if($query->TIDO_VALIDAR == 1){
								return '<span class="badge badge-success">Validar</span>';
							}
							return '<span class="badge badge-info">No validar</span>';
						})
						->addColumn('opciones',function($query){
							$buttons = '';
							
								$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-warning" onclick="hTipoDocumento.disable('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-level-down"></i></button>';


								$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-trash"></i></button>';
								
								$buttons .= '<input type="checkbox" value="'.$query->TIDO_TIPO_DOCUMENTO.'"> Marcar';

								return $buttons;
						},1)
						->filterColumn('id', function($query, $keyword){
							$query->whereRaw("id like ?", ["%{$keyword}%"]);
						})
						->filterColumn('nombre', function($query, $keyword){
							$query->whereRaw("nombre like ?", ["%{$keyword}%"]);
						})
						->filterColumn('fecha', function($query, $keyword){
							$query->whereRaw("fecha like ?", ["%{$keyword}%"]);
						})
						->removeColumn('TIDO_VALIDAR')
						->rawColumns(['validar','opciones'])
						->make();

		/*
		return DataTables::collection($data)
						->showColumns('TIDO_TIPO_DOCUMENTO','TIDO_NOMBRE_TIPO','TIDO_CREATED_AT')
						->addColumn('Validar',function($query){

							if($query->TIDO_VALIDAR) return '<span class="badge badge-success">Validar</span>';
							return '<span class="badge badge-info">No validar</span>';


						})->addColumn('Opciones',function($query){

								

						})
						->searchColumns('TIDO_TIPO_DOCUMENTO','TIDO_NOMBRE_TIPO','TIDO_CREATED_AT')
						->make();
		*/
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
