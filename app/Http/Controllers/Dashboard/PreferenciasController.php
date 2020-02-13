<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

class PreferenciasController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this -> setLog('PreferenciasController.log');
	}

	public function index(){

		return view('Dashboard.indexDashboard');
	}

}
