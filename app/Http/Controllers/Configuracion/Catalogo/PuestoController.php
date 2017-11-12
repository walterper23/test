<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\BaseController;
use Validator;

use App\Model\MDepartamento;
use App\Model\MPuesto;

use Yajra\DataTables\DataTables;

class PuestoController extends BaseController{

	public function index(DataTables $datatables){

		$columns = ['#','id','nombre','departamento','fecha','opciones'];

		$data['table'] = $datatables->getHtmlBuilder()
							->columns($columns)	
							->parameters([
								'dom'       => 'Bfrtip',
								'buttons'   => ['reload','export'],
								'saveState' => true
							])
							->ajax([
								'method'    => 'POST',
								'url'       => url('configuracion/catalogos/puestos/post-data')
							]);

		return view('Configuracion.Catalogo.Puesto.indexPuesto')->with($data);
	}

	public function manager(Request $request){

	}

	public function postDataTable(){

		$data = MPuesto::selectRaw('PUES_PUESTO as id, PUES_NOMBRE as nombre, PUES_CREATED_AT as fecha, PUES_ENABLED')
						->where('PUES_DELETED',0);

		return DataTables::of($data)
			->addColumn('#', function($query){
				return '<div class="custom-controls-stacked">
		                    <label class="custom-control custom-checkbox">
		                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->PUES_PUESTO.'">
		                        <span class="custom-control-indicator"></span>
		                    </label>
		                </div>';
			})
			->addColumn('departamento',function($query){
				return 'nombre del departamento';
			})
			->addColumn('opciones',function($query){
					$buttons = '';

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->PUES_PUESTO.')"><i class="fa fa-eye"></i></button>';

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->PUES_PUESTO.')"><i class="fa fa-pencil"></i></button>';
				
					if($query->PUES_ENABLED == 1){
						$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->PUES_PUESTO.')"><i class="fa fa-level-down"></i></button>';
					}else{
						$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.enabled('.$query->PUES_PUESTO.')"><i class="fa fa-level-up"></i></button>';
					}

					$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->PUES_PUESTO.')"><i class="fa fa-trash"></i></button>';
					
					return $buttons;
			},1)
			->removeColumn('PUES_ENABLED')
			->rawColumns(['#','opciones'])
			->make();
	}

	public function formPuesto(){
		try{

			$data = [
				'title'         => 'Nuevo puesto',
				'url_send_form' => '',
				'form_id'       => '',
			];

			$data['departamentos'] = MDepartamento::select('DEPA_DEPARTAMENTO','DEPA_NOMBRE')
									->pluck('DEPA_NOMBRE','DEPA_DEPARTAMENTO')
									->toArray();

			return view('Configuracion.Catalogo.Puesto.formPuesto')->with($data);

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
