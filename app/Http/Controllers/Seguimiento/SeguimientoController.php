<?php

namespace App\Http\Controllers\Seguimiento;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

class SeguimientoController extends BaseController{


	public function __construct(){
		$this->setLog('perfil.log');
	}

	public function index(){
		return view('Dashboard.index');
	}

}
