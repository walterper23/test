<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\BaseController;
use Validator;


use Yajra\DataTables\DataTables;
use App\Model\MDireccion;


class DireccionController extends BaseController{

	public function index(DataTables $datatables){


		$columns = ['#','id','nombre','fecha','opciones'];

		$data['table'] = $datatables->getHtmlBuilder()
							->columns($columns)	
							->parameters(['dom' => 'Bfrtip',
								'buttons' => ['reload','export'],
								'saveState'=>true])
							->ajax([
								'method'=>'POST',
								'url'=>url('configuracion/catalogos/direcciones/post-data')]);

		//
		return view('Configuracion.Catalogo.Direccion.indexDireccion')->with($data);

	}

	public function manager(Request $request){

	}

	public function postDataTable(){

		$data = MDireccion::selectRaw(' as id, TIDO_NOMBRE_TIPO as nombre, TIDO_CREATED_AT as fecha, TIDO_VALIDAR, TIDO_ENABLED')->where('TIDO_DELETED',0);

		return DataTables::of($data)
			->addColumn('#', function($query){
				return '<div class="custom-controls-stacked">
		                    <label class="custom-control custom-checkbox">
		                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->TIDO_TIPO_DOCUMENTO.'">
		                        <span class="custom-control-indicator"></span>
		                    </label>
		                </div>';
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
			
			->removeColumn('TIDO_VALIDAR, TIDO_ENABLED')
			->rawColumns(['#','opciones'])
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
	public function formDireccion(){
		try{

			$data =[];

			$data['title']='Nueva Dirección';
			$data['url_send_form']='';
			$data['form_id']='';
			
				
			return view('configuracion.Catalogo.Direccion.formDireccion')-> with ($data);

		}catch(Exception $error){

		}
	}

	public function postNuevaDireccion(){
		try{
		
		}catch(Exception $error){

		}
	}

	public function postEditarDireccion(){
		try{

		}catch(Exception $error){
			
		}
	}

	public function eliminarDireccion( $id ){
		try{

		}catch(Exception $error){
		}
	}

}
