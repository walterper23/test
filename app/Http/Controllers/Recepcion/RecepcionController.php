<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerRecepcionRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;
use Exception;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\MDocumento;
use App\Model\MDenuncia;
use App\Model\MSeguimiento;
use App\Model\MMunicipio;
use App\Model\MDetalle;
use App\Model\Catalogo\MAnexo;
use App\Model\Catalogo\MEstadoDocumento;
use App\Model\Catalogo\MDireccion;
use App\Model\Sistema\MSistemaTipoDocumento;

/* DataTables */
use App\DataTables\DenunciasDataTable;
use App\DataTables\DocumentosDenunciasDataTable;
use App\DataTables\DocumentosDataTable;

class RecepcionController extends BaseController {

	public function index(){

		$tabla = new DenunciasDataTable();

		$data['table1'] = $tabla;
		//$data['table2'] = new DocumentosDenunciasDataTable();
		//$data['table3'] = new DocumentosDataTable();

		return view('Recepcion.indexRecepcion') -> with($data);
	}

	public function manager(ManagerRecepcionRequest $request){

		switch ($request -> action) {
			case 0: // Guardar recepción en captura
				$response = $this -> capturarRecepcion( $request );
			case 1: // Nueva recepción
				$response = $this -> nuevaRecepcion( $request );
				break;
			case 2: // Editar
				$response = $this -> editarAnexo( $request );
				break;
			case 3: // Activar / Desactivar
				$response = $this -> activarAnexo( $request );
				break;
			case 4: // Eliminar
				$response = $this -> eliminarAnexo( $request );
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(DocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formNuevaRecepcion(){

		$data = [];

		$data['tipos_documentos'] = MSistemaTipoDocumento::select('SYTD_NOMBRE_TIPO','SYTD_TIPO_DOCUMENTO','SYTD_ETIQUETA_NUMERO')
									-> where('SYTD_ENABLED',1) -> get();

		$data['anexos'] = MAnexo::select('ANEX_ANEXO','ANEX_NOMBRE')
									->where('ANEX_ENABLED',1)
									->where('ANEX_DELETED',0)
									->orderBy('ANEX_NOMBRE')
									->pluck('ANEX_NOMBRE','ANEX_ANEXO')
									->toArray();

		$data['municipios'] = MMunicipio::selectRaw('MUNI_MUNICIPIO AS id, CONCAT(MUNI_CLAVE," - ",MUNI_NOMBRE) AS nombre') -> where('MUNI_ENABLED',1)
							-> pluck('nombre','id') -> toArray();

		$data['context']       = 'context-form-recepcion';
		$data['form_id']       = 'form-recepcion';
		$data['url_send_form'] = url('recepcion/documentos/manager');

		$data['form'] = view('Recepcion.formNuevaRecepcion') -> with($data);

		unset($data['tipos_documentos']);

		return view('Recepcion.nuevaRecepcion') -> with($data);

	}

	public function nuevaRecepcion( $request ){

		try{

			$anexos = preg_replace('/\r|\n/','\n',$request -> anexos);
			$anexos = str_replace('\n\n','\n',$anexos);

			DB::beginTransaction();

			$detalle = new MDetalle;
			$detalle -> DETA_MUNICIPIO            = $request -> municipio;
			$detalle -> DETA_FECHA_RECEPCION      = $request -> recepcion;
			$detalle -> DETA_DESCRIPCION          = $request -> descripcion;
			$detalle -> DETA_RESPONSABLE          = $request -> responsable;
			$detalle -> DETA_ANEXOS               = $anexos;
			$detalle -> DETA_OBSERVACIONES        = $request -> observaciones;
			$detalle -> save();

			$documento = new MDocumento;
			$documento -> DOCU_SYSTEM_TIPO_DOCTO   = $request -> tipo_documento;
			$documento -> DOCU_SYSTEM_ESTADO_DOCTO = 2; // Documento recepcionado
			$documento -> DOCU_DETALLE             = $detalle -> getKey();
			$documento -> DOCU_NUMERO_DOCUMENTO    = $request -> numero;
			$documento -> save();

			if ($request -> tipo_documento == 1) // Si el tipo de documento es denuncia ...
			{
				$denuncia = new MDenuncia; // ... crear el registro de la denuncia
				$denuncia -> DENU_DOCUMENTO = $documento -> getKey();
				$denuncia -> save();
			}

			$seguimiento = new MSeguimiento;
			$seguimiento -> SEGU_USUARIO              = userKey();
			$seguimiento -> SEGU_DOCUMENTO            = $documento -> getKey();
			$seguimiento -> SEGU_DIRECCION_ORIGEN     = config_var('Sistema.Direcc.Origen'); // Dirección de la recepción, por default
			$seguimiento -> SEGU_DEPARTAMENTO_ORIGEN  = config_var('Sistema.Depto.Origen'); // Departamento de la recepción, por default
			$seguimiento -> SEGU_DIRECCION_DESTINO    = config_var('Sistema.Direcc.Destino'); // Dirección del procurador, por default
			$seguimiento -> SEGU_DEPARTAMENTO_DESTINO = config_var('Sistema.Depto.Destino'); // Departamento del procurador, por default
			$seguimiento -> SEGU_ESTADO_DOCUMENTO     = 1; // Documento recepcionado. Estado de documento por default
			$seguimiento -> save();

			DB::commit();

			return $this -> responseSuccessJSON();

		}catch(Exception $error){
			DB::rollback();
			dd( $error->getMessage() );
		}

	}



	public function verDocumentoRecepcionado( $id ){

		$data['documento'] = MDocumento::find( $id );

		return view('Recepcion.verDocumento')->with($data);

	}

	public function verSeguimiento( $id ){

		$data['documento'] = MDocumento::find( $id );

		return view('Seguimiento.verSeguimiento')->with($data);
	}



	public function modalCambio(){

		$data = [
			'title'         => 'Cambio de Estado de Documento',
			'url_send_form' => url('guardar-cambio'),
			'form_id'       => 'modal-cambio',
			'modelo'        => MDocumento::find(1),
			'action'        => 1,
			'id'            => 1
		];

		$direcciones = MDireccion::with('departamentos')
							->select('DIRE_DIRECCION','DIRE_NOMBRE')
							->where('DIRE_ENABLED',1)
							->orderBy('DIRE_NOMBRE')
							->get();

		$data['direcciones'] = $direcciones->pluck('DIRE_NOMBRE','DIRE_DIRECCION')->toArray();

		$data['departamentos'] = [];

		foreach ($direcciones as $direccion) {

			$nombre_direccion = $direccion->DIRE_NOMBRE;
			foreach($direccion->departamentos as $departamento){
				$id_departamento      = $departamento->DEPA_DEPARTAMENTO;
				$nombre_departamento  = $departamento->DEPA_NOMBRE;
				$data['departamentos'][ $nombre_direccion ][ $id_departamento ] = $nombre_departamento;
			}

		}

		$data['departamentos'][0] = '- Ninguno -';

		$estados = MEstadoDocumento::all()->pluck('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO')->toArray();

		$data['estados'] = $estados;

		return view('modalCambio')->with($data);
	}

	public function guardarCambio(){

		$seguimiento = new MSeguimiento;

		$seguimiento->SEGU_DOCUMENTO = 1;
		$seguimiento->SEGU_DIRECCION = 2;
		$seguimiento->SEGU_DEPARTAMENTO = 4;
		$seguimiento->SEGU_USUARIO = 1;
		$seguimiento->SEGU_ESTADO_DOCUMENTO = Input::get('estado');
		$seguimiento->SEGU_OBSERVACION = Input::get('observacion');

		$seguimiento->save();

		return response()->json(['status'=>true, 'message'=>'<i class="fa fa-check"></i> El cambio de estado se realizó correctamente']);

	}

}
