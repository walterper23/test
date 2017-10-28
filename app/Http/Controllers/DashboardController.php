<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends BaseController{


	public function __construct(){

	}

	public function index(){
		return view('Administrador.Catalogos.index');
	}

}
