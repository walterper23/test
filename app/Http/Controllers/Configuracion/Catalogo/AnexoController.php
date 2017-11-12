<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\BaseController;
use Validator;

use App\Model\MAnexo;


use Yajra\DataTables\DataTables;

class AnexoController extends BaseController{
	public function index(DataTables $dataTables){

		$columns = ['#','id','nombre','fecha','opciones'];

		$data['table'] = $dataTables->getHtmlBuilder()//dibuja la tabla con los parámetros
							->columns($columns)	
							->parameters(['dom' => 'Bfrtip','buttons' => ['reload','export'],'saveState'=>true])
							->ajax(['method'=>'POST','url'=>url('configuracion/catalogos/anexos/post-data')]);//recibe un array de dos índices, metodo cuando la tabla se dibuje va a ser POST y se le manda la direcciones donde va a buscar los datos



		return view("Configuracion.Catalogo.Anexo.indexAnexo")->with($data);

	}

	public function manager(Request $request){

	}

	public function postDataTable(){

		$data = MAnexo::selectRaw('ANEX_ANEXO as id, ANEX_NOMBRE as nombre, ANEX_ENABLED ,ANEX_CREATED_AT as fecha')->where('ANEX_DELETED',0);

		return DataTables::of($data)
			->addColumn('#', function($query){
				return '<div class="custom-controls-stacked">
		                    <label class="custom-control custom-checkbox">
		                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->ANEX_ANEXO.'">
		                        <span class="custom-control-indicator"></span>
		                    </label>
		                </div>';
			})
			->addColumn('opciones',function($query){
					$buttons = '';

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->ANEX_ANEXO.')"><i class="fa fa-eye"></i></button>';

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->ANEX_ANEXO.')"><i class="fa fa-pencil"></i></button>';
				
					if($query->TIDO_ENABLED == 1){
						$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->ANEX_ANEXO.')"><i class="fa fa-level-down"></i></button>';
					}else{
						$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.enabled('.$query->ANEX_ANEXO.')"><i class="fa fa-level-up"></i></button>';
					}

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->ANEX_ANEXO.')"><i class="fa fa-trash"></i></button>';
					
					return $buttons;
			},1)
						
			->removeColumn('ANEX_ENABLED')
			->rawColumns(['#','opciones'])
			->make();
	}

	

	public function formAnexo(){
		try{

			$data = [];

			$data['title'] = 'Nuevo anexo';
			$data['url_send_form'] ='';
			$data['form_id'] = '';

			/*$data =[
				'' => 'Selecciona una opción',
				1 => 'General',				
			];*/



			return view('Configuracion.Catalogo.Anexo.formAnexo')-> with ($data);

		}catch(Exception $error){

		}
	}

	public function postNuevoPuesto(){
		try{
		
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

		}catch(Exception $error){

		}
	}

}