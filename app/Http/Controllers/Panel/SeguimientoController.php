<?php
namespace App\Http\Controllers\Panel;

use App\Http\Controllers\BaseController; 
use Illuminate\Http\Request;

/* Models */
use App\Model\MSeguimiento;
use App\Model\Catalogo\MDireccion;


class SeguimientoController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this->setLog('SeguimientoController.log');
	}

	public function index(Request $request)
	{
		$search = $request->get('search');
		$read   = $request->get('read',0);

		$seguimiento = MSeguimiento::with('Documento')->find( $search );

		if (is_null($seguimiento))
			return view('Panel.Seguimiento.seguimientoNoEncontrado');

		if ($read == 1 && !$seguimiento->leido() ) // Si la petición pide que marquemos el seguimiento como leído y el seguimiento no ha sido leido ...
			$seguimiento->marcarComoLeido()->save(); // ... marcamos el seguimiento como leído

		$data['seguimiento']  = $seguimiento;
		$data['documento']    = $seguimiento->Documento;
		$data['detalle']      = $seguimiento->Documento->Detalle;
		$data['seguimientos'] = $seguimiento->Documento->Seguimientos()
			-> with(['DireccionOrigen','DireccionDestino','DepartamentoOrigen','DepartamentoDestino','EstadoDocumento','Dispersiones'=>function($dispersion){
				$dispersion->with('Direccion','Departamento');
			}])
			-> leftJoin('usuarios','USUA_USUARIO','=','SEGU_USUARIO')
			-> leftJoin('usuarios_detalles','USDE_USUARIO_DETALLE','=','USUA_DETALLE')
			-> existente()
			-> orderBy('SEGU_SEGUIMIENTO','DESC')
			-> get();

		// Obtener todas las direcciones de destino existentes y disponibles con sus departamentos existentes y disponibles
        $direcciones = MDireccion::with('DepartamentosExistentesDisponibles')
                       ->select('DIRE_DIRECCION','DIRE_NOMBRE')
                       ->existenteDisponible()
                       ->orderBy('DIRE_NOMBRE')
                       ->get();

        $data['direcciones'] = $direcciones;

		return view('Panel.Seguimiento.verSeguimiento')->with($data);
	}

}