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
		$read   = $request -> get('read',0);

		$seguimiento = MSeguimiento::with('Documento') -> find( $search );

		if ($read == 1)
			$seguimiento -> marcarComoLeido() -> save();

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
