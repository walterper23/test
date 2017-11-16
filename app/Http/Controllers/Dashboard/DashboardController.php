<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

class DashboardController extends BaseController{


	public function __construct(){

	}

	public function index(){

		return view('Dashboard.index');
	}

}
