<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

class PreferenciasController extends BaseController {


	public function __construct(){
		$this -> setLog('perfil.log');
	}

	public function index(){

		$this -> Log('info','Prueba 1 de Log');

		return view('Dashboard.index');
	}

}
