<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

/* Models */
use App\Model\MNotificacion;

class NotificacionController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this->setLog('NotificacionController.log');
	}

	public function index(){

		return view('Dashboard.indexDashboard');
	}



	public function nuevaNotificacion( $type, $data )
	{

		switch ($type) {
			case 'value':
				# code...
				break;
			
			default:
				# code...
				break;
		}




		$notificacion = new MNotificacion;

		$notificacion -> save();

	}

}