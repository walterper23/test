<?php
namespace App\Http\Controllers\Configuracion\Sistema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\Sistema\MSistemaTipoDocumento;

class SistemaVariableController extends BaseController
{

	private $form_id;

	public function index(){

		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/sistema/tipos-documentos/nuevo');

		return view('Configuracion.Sistema.Variables.indexVariables') -> with($data);
	}

	public function manager(ManagerTipoDocumentoRequest $request){

		switch ($request -> action) {
			case 2: // Editar
				$response = $this -> editarTipoDocumento( $request );
				break;
			case 3: // Activar / Desactivar
				$response = $this -> activarTipoDocumento( $request );
				break;
			case 5: // Validar tipo de documento
				$response = $this -> validarTipoDocumento( $request );
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

}