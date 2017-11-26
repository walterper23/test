<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

use App\DataTables\DocumentosDataTable;

class RecepcionController extends BaseController{

	public function __construct(){

	}

	public function index(DocumentosDataTable $dataTables){
		return view('Recepcion.indexRecepcion')->with('table', $dataTables);
	}

	public function postDataTable(DocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function nuevaRecepcion(){

		$data = [];

		$data['tipos_documentos'] = [];

		$data['form'] = view('Recepcion.formNuevaRecepcion')->with($data);

		return view('Recepcion.nuevaRecepcion')->with($data);

	}

}
