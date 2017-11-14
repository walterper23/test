<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\PuestosDataTable;

/* Models */
use App\Model\MAnexo;

class PuestoController extends BaseController{

	public function index(PuestosDataTable $dataTables){
		return view('Configuracion.Catalogo.Puesto.indexPuesto')->with('table', $dataTables);
	}

	public function postDataTable(PuestosDataTable $dataTables){
		return $dataTables->getData();
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
