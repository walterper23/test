<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\DireccionesDataTable;

/* Models */
use App\Model\MDireccion;

class DireccionController extends BaseController{

	public function index(DireccionesDataTable $dataTables){
		return view('Configuracion.Catalogo.Direccion.indexDireccion')->with('table', $dataTables);
	}

	public function postDataTable(DireccionesDataTable $dataTables){
		return $dataTables->getData();
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

	public function postNuevaDireccion(Requests $requests){
		try{
		$data['title']=$requests->input('nombre direccion');
		DB::table('cat_direcciones')->insert(['DIRE_NOMBRE'->$data['title']]);
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
