<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\AnexosDataTable;

/* Models */
use App\Model\Catalogo\MAnexo;

class AnexoController extends BaseController{

	public function index(AnexosDataTable $dataTables){
		return view("Configuracion.Catalogo.Anexo.indexAnexo")->with('table', $dataTables);
	}

	public function postDataTable(AnexosDataTable $dataTables){
		return $dataTables->getData();
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