<?php
namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MDocumentoSemaforizado;

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

	public function verSeguimiento(Request $request)
	{
		// Recuperamos la solicitud o semáforo
		$semaforo = MDocumentoSemaforizado::find( $request -> id );

		if ($request -> type == 1) // Si el seguimiento solicitado, es el de la solicitud
		{
			$data['title']       = 'Solicitud realizada #' . $semaforo -> getCodigo();
			$data['seguimiento'] = $semaforo -> SeguimientoA;
		}
		else // Si el seguimiento solicituado, es el de la respuesta
		{
			$data['title']       = 'Respuesta a solicitud #' . $semaforo -> getCodigo();
			$data['seguimiento'] = $semaforo -> SeguimientoB;
		}

		// Recuperamos al usuario que creo el seguimiento donde hizo la solicitud, o donde respondió a la solicitud
		$usuario = $data['seguimiento'] -> Usuario -> UsuarioDetalle;

		$data['type']                = $request -> type;
		$data['semaforo']            = $semaforo;
		$data['seguimiento_usuario'] = trim(sprintf('%s :: %s',$usuario -> getNoTrabajador(), $usuario -> presenter() -> nombreCompleto())); 

		return view('Panel.Documentos.verSeguimiento') -> with($data);
	}

}