<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\BaseController;
use Validator;
use App\Model\MDireccion;

use Yajra\DataTables\DataTables;
use App\Model\MDepartamento;

class DepartamentoController extends BaseController{
	public function index(DataTables $datatables){
		$columns = ['#','id','nombre','direccion','fecha','opciones'];

		$data['table'] = $datatables->getHtmlBuilder()
							->columns($columns)	
							->parameters(['dom' => 'Bfrtip','buttons' => ['reload','export'],'saveState'=>true])
							->ajax(['method'=>'POST','url'=>url('configuracion/catalogos/departamentos/post-data')]);



      return view('Configuracion.Catalogo.Departamento.indexDepartamento')->with($data);

	}

	public function manager(Request $request){

	}

	
		
	

	public function formDepartamento(){
		try{

			$data =[];
			$data['title']= 'Nuevo Departamento';
			$data['url_send_form']= '';
			$data['form_id']='';
			$data['direcciones']=MDireccion::select('DIRE_DIRECCION','DIRE_NOMBRE')->pluck('DIRE_NOMBRE','DIRE_DIRECCION')->toArray();

			return view('Configuracion.Catalogo.Departamento.formDepartamento')->with($data);

		}catch(Exception $error){

		}
	}



	public function postNuevoDepartamento(){
		try{
		
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

		}catch(Exception $error){

		}
	}


	public function postDataTable(){

		$data = MDepartamento::selectRaw('DEPA_DEPARTAMENTO as id, DEPA_NOMBRE as nombre, DEPA_CREATED_AT as fecha , DEPA_ENABLED')->where('DEPA_DELETED',0);

		return DataTables::of($data)
			->addColumn('#', function($query){
				return '<div class="custom-controls-stacked">
		                    <label class="custom-control custom-checkbox">
		                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->DEPA_DEPARTAMENTO.'">
		                        <span class="custom-control-indicator"></span>
		                    </label>
		                </div>';
			})

			->addColumn('direccion',function($query){

				return "Nombre de la direccion";
			})
			
			->addColumn('opciones',function($query){
					$buttons = '';

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-eye"></i></button>';

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-pencil"></i></button>';
				
					if($query->TIDO_ENABLED == 1){
						$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-level-down"></i></button>';
					}else{
						$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.enabled('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-level-up"></i></button>';
					}

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-trash"></i></button>';
					
					return $buttons;
			},1)
			
		
			->removeColumn(' DEPA_ENABLED')
			->rawColumns(['#','opciones'])
			->make();
	}

}
