<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\BaseController;
use App\DataTables\TiposDocumentosDataTable;

use App\Model\Catalogo\MTipoDocumento;


use Validator;

class TipoDocumentoController extends BaseController{

	public function index(TiposDocumentosDataTable $datatables){

		

		/*
		//$columns = ['#','id','nombre','fecha','validar','opciones'];

		$data['table'] = $datatables->getHtmlBuilder()
							->columns($columns)	
							->parameters(['dom' => 'Bfrtip','buttons' => ['reload','export'],'saveState'=>true])
							->ajax();
		*/

		return view('Configuracion.Catalogo.TipoDocumento.indexTipoDocumento')->with('table', $datatables);
	}

	public function manager(Request $request){

	}

	public function postDataTable(){

		$data = MTipoDocumento::selectRaw('TIDO_TIPO_DOCUMENTO as id, TIDO_NOMBRE_TIPO as nombre, TIDO_CREATED_AT as fecha, TIDO_VALIDAR, TIDO_ENABLED')->where('TIDO_DELETED',0);

		return DataTables::of($data)
			->addColumn('#', function($query){
				return '<div class="custom-controls-stacked">
		                    <label class="custom-control custom-checkbox">
		                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->TIDO_TIPO_DOCUMENTO.'">
		                        <span class="custom-control-indicator"></span>
		                    </label>
		                </div>';
			})
			->addColumn('validar', function($query){
				if($query->TIDO_VALIDAR == 1){
					return '<span class="badge badge-success">Validar</span>';
				}
				return '<span class="badge badge-info">No validar</span>';
			})
			->addColumn('opciones',function($query){
					$buttons = '';

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-eye"></i></button>';

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-pencil"></i></button>';
				
					if($query->TIDO_ENABLED == 1){
						$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-level-down"></i></button>';
					}else{
						$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.enabled('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-level-up"></i></button>';
					}

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-trash"></i></button>';
					
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
			//->setRowClass(function($query){
			//	return $query->TIDO_ENABLED == 1 ? '' : 'alert-danger';
			//})
			->orderColumn('nombre', '-nombre $1')
			->removeColumn('TIDO_VALIDAR, TIDO_ENABLED')
			->rawColumns(['#','validar','opciones'])
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
