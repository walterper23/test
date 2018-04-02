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
use App\Model\MArchivo;
use App\Model\MDenuncia;
use App\Model\MDetalle;
use App\Model\MDocumento;
use App\Model\MEscaneo;
use App\Model\MMunicipio;
use App\Model\MSeguimiento;
use App\Model\Catalogo\MAnexo;
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MEstadoDocumento;
use App\Model\Sistema\MSistemaTipoDocumento;

/* DataTables */
use App\DataTables\DenunciasDataTable;
use App\DataTables\DocumentosDenunciasDataTable;
use App\DataTables\DocumentosDataTable;

class RecepcionController extends BaseController
{

	public function index(){

		$tabla1 = new DenunciasDataTable();
		$tabla2 = new DocumentosDenunciasDataTable();
		$tabla3 = new DocumentosDataTable();

		$data['table1'] = $tabla1;
		$data['table2'] = $tabla2;
		$data['table3'] = $tabla3;

		return view('Recepcion.indexRecepcion') -> with($data);
	}

	public function manager(ManagerRecepcionRequest $request){

		switch ($request -> action) {
			case 0: // Guardar recepción en captura
				$response = $this -> capturarRecepcion( $request );
				break;
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

	public function postDataTable(Request $request){

		$type = $request -> get('type');

		switch ($type) {
			case 'denuncias' :
				$dataTables = new DenunciasDataTable;
				break;
			case 'documentos-denuncias':
				$dataTables = new DocumentosDenunciasDataTable;
				break;
			case 'documentos':
				$dataTables  = new DocumentosDataTable;
				break;
			default:
				$dataTables  = new DocumentosDataTable;
				break;
		}


		return $dataTables -> getData();
	}

	public function formNuevaRecepcion(){

		$data = [];

		// Recuperamos los tipos de documentsos que se pueden recepcionar
		$data['tipos_documentos'] = MSistemaTipoDocumento::where('SYTD_ENABLED',1) -> get();

		$data['anexos'] = MAnexo::where('ANEX_ENABLED',1)
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

			// Reemplazamos los saltos de línea, por "\n"
			$anexos = preg_replace('/\r|\n/','\n',$request -> anexos);
			// Reemplazamos las "\n" repetidas
			$anexos = str_replace('\n\n','\n',$anexos);

			DB::beginTransaction();

			// Guardamos los detalles del documento
			$detalle = new MDetalle;
			$detalle -> DETA_MUNICIPIO            = $request -> municipio;
			$detalle -> DETA_FECHA_RECEPCION      = $request -> recepcion;
			$detalle -> DETA_DESCRIPCION          = $request -> descripcion;
			$detalle -> DETA_RESPONSABLE          = $request -> responsable;
			$detalle -> DETA_ANEXOS               = $anexos;
			$detalle -> DETA_OBSERVACIONES        = $request -> observaciones;
			$detalle -> save();

			// Guardamos el documento
			$documento = new MDocumento;
			$documento -> DOCU_SYSTEM_TIPO_DOCTO   = $request -> tipo_documento;
			$documento -> DOCU_SYSTEM_ESTADO_DOCTO = 2; // Documento recepcionado
			$documento -> DOCU_DETALLE             = $detalle -> getKey();
			$documento -> DOCU_NUMERO_DOCUMENTO    = $request -> numero;
			$documento -> save();

			// Guardamos el primer seguimiento del documento
			$seguimiento = new MSeguimiento;
			$seguimiento -> SEGU_USUARIO              = userKey();
			$seguimiento -> SEGU_DOCUMENTO            = $documento -> getKey();
			$seguimiento -> SEGU_DIRECCION_ORIGEN     = config_var('Sistema.Direcc.Origen'); // Dirección de la recepción, por default
			$seguimiento -> SEGU_DEPARTAMENTO_ORIGEN  = config_var('Sistema.Depto.Origen'); // Departamento de la recepción, por default
			$seguimiento -> SEGU_DIRECCION_DESTINO    = config_var('Sistema.Direcc.Destino'); // Dirección del procurador, por default
			$seguimiento -> SEGU_DEPARTAMENTO_DESTINO = config_var('Sistema.Depto.Destino'); // Departamento del procurador, por default
			$seguimiento -> SEGU_ESTADO_DOCUMENTO     = 1; // Documento recepcionado. Estado de documento por default
			$seguimiento -> save();
			
			if ($request -> tipo_documento == 1) // Si el tipo de documento es denuncia ...
			{
				$denuncia = new MDenuncia; // ... crear el registro de la denuncia
				$denuncia -> DENU_DOCUMENTO = $documento -> getKey();
				$denuncia -> save();
			}

			// Guardamos los archivos o escaneos que se hayan agregado al archivo

			DB::commit();

			return redirect('recepcion/documentos/recepcionados');

		}catch(Exception $error){
			DB::rollback();
			dd($error->getMessage());
		}

	}

	// Método para agregar un nuevo archivo a un documento
	public function nuevoEscaneo(MDocumento $documento, $file, $data)
	{

		$filename = sprintf('escaneo_docto_%d_%s.pdf',$documento -> getKey(),time());

		$archivo = new MArchivo;
		$archivo -> ARCH_FOLDER   = '';
		$archivo -> ARCH_FILENAME = $filename;
		$archivo -> ARCH_PATH     = '';
		$archivo -> ARCH_TYPE     = '';
		$archivo -> ARCH_MIME     = '';
		$archivo -> ARCH_SIZE     = '';
		$archivo -> save();

		$escaneo = new MEscaneo;
		$escaneo -> ESCA_ARCHIVO     = $archivo -> getKey(); 
		$escaneo -> ESCA_DOCUMENTO   = $documento -> getKey(); 
		$escaneo -> ESCA_NOMBRE      = $data['escaneo_nombre']; 
		$escaneo -> ESCA_DESCRIPCION = $data['escaneo_descripcion']; 
		$escaneo -> save();

	}



	public function verDocumentoRecepcionado( $id ){

		$data['documento'] = MDocumento::find( $id );

		return view('Recepcion.verDocumento')->with($data);

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
