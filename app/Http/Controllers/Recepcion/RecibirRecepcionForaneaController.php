<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Exception;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MDenuncia;
use App\Model\MDetalle;
use App\Model\MDocumento;
use App\Model\MDocumentoForaneo;
use App\Model\MDocumentoDenuncia;
use App\Model\MEscaneo;
use App\Model\MMunicipio;
use App\Model\MSeguimiento;
use App\Model\Catalogo\MAnexo;
use App\Model\Sistema\MSistemaTipoDocumento;

/* DataTables */
use App\DataTables\RecibirDenunciasForaneasDataTable;
use App\DataTables\RecibirDocumentosDenunciasForaneasDataTable;
use App\DataTables\RecibirDocumentosForaneosDataTable;

class RecibirRecepcionForaneaController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this -> setLog('RecibirRecepcionForaneaController.log');
	}

	public function index(Request $request){

		$tabla1 = new RecibirDenunciasForaneasDataTable();
		$tabla2 = new RecibirDocumentosDenunciasForaneasDataTable();
		$tabla3 = new RecibirDocumentosForaneosDataTable();

		$data['table1'] = $tabla1;
		$data['table2'] = $tabla2;
		$data['table3'] = $tabla3;

		$view  = $request -> get('view','denuncias');
		$acuse = $request -> get('acuse');

		$data['tab_1'] = '';
		$data['tab_2'] = '';
		$data['tab_3'] = '';

		switch ( $view ) {
			case 'denuncias':
				$data['tab_1'] = ' active';
				break;
			case 'documentos-denuncias':
				$data['tab_2'] = ' active';
				break;
			case 'documentos':
				$data['tab_3'] = ' active';
				break;
			default:
				$data['tab_1'] = ' active';
				break;
		}

		$data['acuse'] = $acuse;

		return view('Recepcion.indexRecibirRecepcionForanea') -> with($data);
	}

	public function documentosEnCaptura()
	{
		abort(404);
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

	public function postDataTable(Request $request){

		$type = $request -> get('type');

		switch ($type) {
			case 'denuncias' :
				$dataTables = new RecibirDenunciasForaneasDataTable;
				break;
			case 'documentos-denuncias':
				$dataTables = new RecibirDocumentosDenunciasForaneasDataTable;
				break;
			case 'documentos':
				$dataTables  = new RecibirDocumentosForaneosDataTable;
				break;
			default:
				$dataTables  = new RecibirDocumentosForaneosDataTable;
				break;
		}

		return $dataTables -> getData();
	}

	public function recibirDocumento( $request )
	{
		$documento = MDocumentoForaneo::find( $request -> id);

		if ($documento -> recibido())
		{
			// El documento ya fue recibido
		}

		$documento -> DOFO_SYSTEM_TRANSITO = 2; // Documento recibido en Oficialía de Partes
		$documento -> save();

		if ($documento -> getTipoDocumento() == 1)
			$tables = ['recibir-denuncias-datatable',null,true];
		else if ($documento -> getTipoDocumento() == 2)
			$tables = ['recibir-documentos-denuncias-datatable',null,true];
		else
			$tables = ['recibir-documentos-datatable',null,true];

		$message = sprintf('Documento foráneo <b>#%s</b> recibido', $documento -> getCodigo());

		return $this -> responseSuccessJSON($message,$tables);
	}

	public function validarDocumento( $request )
	{
		$documento = MDocumentoForaneo::find( $request -> id);
		
		if ($documento -> validado())
		{
			// El documento ya fue validado
		}

		$documento -> DOFO_VALIDADO = 1; // Documento validado por Oficialía de Partes
		$documento -> save();

		if ($documento -> getTipoDocumento() == 1)
			$tables = ['recibir-denuncias-datatable',null,true];
		else if ($documento -> getTipoDocumento() == 2)
			$tables = ['recibir-documentos-denuncias-datatable',null,true];
		else
			$tables = ['recibir-documentos-datatable',null,true];

		$message = sprintf('Documento foráneo <b>#%s</b> validado', $documento -> getCodigo());

		return $this -> responseSuccessJSON($message,$tables);
		
	}

	public function recepcionarDocumento( $request )
	{
		try {
			DB::beginTransaction();

				$documentoForaneo = MDocumentoForaneo::find( $request -> id);

				if ($documentoForaneo -> recepcionado())
				{
					// El documento ya fue recepcionado
				}

				$documento = new MDocumento;
				$documento -> DOCU_SYSTEM_TIPO_DOCTO   = $documentoForaneo -> DOFO_SYSTEM_TIPO_DOCTO;
				$documento -> DOCU_SYSTEM_ESTADO_DOCTO = 2; // Documento recepcionado
				$documento -> DOCU_DETALLE             = $documentoForaneo -> DOFO_DETALLE;
				$documento -> DOCU_NUMERO_DOCUMENTO    = $documentoForaneo -> DOFO_NUMERO_DOCUMENTO;
				$documento -> save();

				// Actualizamos el Documento Denuncia si lo generó el documento foráneo, para actualizar el nuevo ID del documento local
				$documentoForaneo -> DocumentoDenuncia() -> update([
					'DODE_DOCUMENTO_LOCAL' => $documento -> getKey()
				]);

				// Actualizamos los escaneos que generó el documento foráneo, colocandoles el nuevo ID del documento local
				$documentoForaneo -> Escaneos() -> update([
					'ESCA_DOCUMENTO_LOCAL' => $documento -> getKey()
				]);

				$documentoForaneo -> DOFO_DOCUMENTO_LOCAL = $documento -> getKey();
				$documentoForaneo -> DOFO_RECEPCIONADO    = 1; // Documento recepcionado por Oficialía de Partes
				$documentoForaneo -> save();

				if ($documentoForaneo -> getTipoDocumento() == 1)
					$tables = ['recibir-denuncias-datatable',null,true];
				else if ($documentoForaneo -> getTipoDocumento() == 2)
					$tables = ['recibir-documentos-denuncias-datatable',null,true];
				else
					$tables = ['recibir-documentos-datatable',null,true];

				$message = sprintf('Documento foráneo <b>#%s</b> recepcionado', $documentoForaneo -> getCodigo());

			DB::commit();

			return $this -> responseSuccessJSON($message,$tables);
		} catch (Exception $error) {
			DB::rollback();
			return $this -> responseDangerJSON($error -> getMessage());
		}
	}

}