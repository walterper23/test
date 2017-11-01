<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

class DashboardAdministradorController extends BaseController{


	public function __construct(){

	}

	public function index(){
		return view('Administrador.Catalogos.index');
	}

}
