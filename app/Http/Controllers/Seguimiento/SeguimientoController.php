<?php
namespace App\Http\Controllers\Panel\Seguimiento;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

/* Models */
use App\Model\MSeguimiento;

class SeguimientoController extends BaseController
{

	public function __construct()
	{
		parent::__construct();
		$this -> setLog('SeguimientoController.log');
	}

	public function index(Request $request)
	{

		$search = $request -> get('search');

		$seguimiento = MSeguimiento::with('Documento') -> find( $search );

		$data['seguimiento']  = $seguimiento;
		$data['documento']    = $seguimiento -> Documento;
		$data['seguimientos'] = $seguimiento -> Documento -> Seguimientos() -> with('DireccionOrigen','DireccionDestino','DepartamentoOrigen','DepartamentoDestino','EstadoDocumento')
			-> leftJoin('usuarios','USUA_USUARIO','=','SEGU_USUARIO')
			-> leftJoin('usuarios_detalles','USDE_USUARIO_DETALLE','=','USUA_DETALLE')
			-> where('SEGU_DELETED',0)
			-> get();

		return view('Panel.Seguimiento.verSeguimiento') -> with($data);
	}

}
