<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

class PerfilController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this->setLog('PerfilController.log');
	}

	public function index()
	{
		return view('Dashboard.index');
	}

}