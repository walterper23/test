<?php
namespace App\Http\Controllers\Reporte;

/* Controllers */
use App\Http\Controllers\BaseController;

class ReporteController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this -> setLog('ReporteController.log');
	}

}