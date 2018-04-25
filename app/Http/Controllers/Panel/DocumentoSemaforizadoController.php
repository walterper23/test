<?php
namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */

/* DataTables */
use App\DataTables\DocumentosSemaforizadosDataTable;

class DocumentoSemaforizadoController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this -> setLog('DocumentoSemaforizadoController.log');
	}

	public function index(DocumentosSemaforizadosDataTable $dataTables){

		$data['table'] = $dataTables;

		return view('Panel.Documentos.documentosSemaforizados') -> with($data);
	}

	public function postDataTable(DocumentosSemaforizadosDataTable $dataTables){
		return $dataTables -> getData();
	}
	
	public function manager(Request $request){

		switch ($request -> action) {
			case 1: // Marcar documentación foránea como recibida
				$response = $this -> recibirDocumento( $request );
				break;
			case 2: // Marcar documentación foránea como validada
				$response = $this -> validarDocumento( $request );
				break;
			case 3: // Recepcionar documentación foránea
				$response = $this -> recepcionarDocumento( $request );
				break;
			default:
				return response() -> json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

}