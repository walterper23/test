<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\DepartamentosDataTable;

/* Models */
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MDepartamento;

class DepartamentoController extends BaseController{
	
	public function index(DepartamentosDataTable $dataTables){
    	return view('Configuracion.Catalogo.Departamento.indexDepartamento')->with('table', $dataTables);
	}

	public function postDataTable(DepartamentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formDepartamento(){
		try{

			$data =[];
			$data['title']= 'Nuevo Departamento';
			$data['url_send_form']= '';
			$data['form_id']='';
			$data['direcciones'] = MDireccion::select('DIRE_DIRECCION','DIRE_NOMBRE')->pluck('DIRE_NOMBRE','DIRE_DIRECCION')->toArray();

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

}
