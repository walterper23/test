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
