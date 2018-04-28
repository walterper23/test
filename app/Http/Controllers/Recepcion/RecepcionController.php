<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerRecepcionRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Filesystem\Filesystem;
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
use App\Model\MDocumentoDenuncia;
use App\Model\MEscaneo;
use App\Model\MMunicipio;
use App\Model\MSeguimiento;
use App\Model\Catalogo\MAnexo;
use App\Model\Sistema\MSistemaTipoDocumento;

/* DataTables */
use App\DataTables\DenunciasDataTable;
use App\DataTables\DocumentosDenunciasDataTable;
use App\DataTables\DocumentosDataTable;

class RecepcionController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this -> setLog('RecepcionController.log');
	}

	public function index(Request $request){

		$tabla1 = new DenunciasDataTable();
		$tabla2 = new DocumentosDenunciasDataTable();
		$tabla3 = new DocumentosDataTable();

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

		return view('Recepcion.indexRecepcion') -> with($data);
	}

	public function documentosEnCaptura()
	{
		abort(404);
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
				$response = $this -> editarRecepcion( $request );
				break;
			case 3: // Visualizar recepción
				$response = $this -> verRecepcion( $request );
				break;
			case 4: // Eliminar
				$response = $this -> eliminarRecepcion( $request );
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
		$data['tipos_documentos'] = MSistemaTipoDocumento::existenteDisponible() -> get();

		$data['denuncias'] = MDenuncia::select('DENU_DENUNCIA','DENU_NO_EXPEDIENTE')
							-> join('documentos','DENU_DOCUMENTO','=','DOCU_DOCUMENTO')
							-> whereNotNull('DENU_NO_EXPEDIENTE')
							-> whereIn('DOCU_SYSTEM_ESTADO_DOCTO',[2,3]) // Documento recepcionado, Documento en seguimiento
							-> pluck('DENU_NO_EXPEDIENTE','DENU_DENUNCIA')
							-> toArray();

		// Recuperamos los anexos almacenados en el sistema
		$data['anexos'] = MAnexo::existenteDisponible()
							-> orderBy('ANEX_NOMBRE')
							-> pluck('ANEX_NOMBRE','ANEX_ANEXO')
							-> toArray();

		$data['municipios'] = MMunicipio::selectRaw('MUNI_MUNICIPIO AS id, CONCAT(MUNI_CLAVE," - ",MUNI_NOMBRE) AS nombre')
							-> where('MUNI_ENABLED',1)
							-> pluck('nombre','id')
							-> toArray();

		$data['context']       = 'context-form-recepcion';
		$data['form_id']       = 'form-recepcion';
		$data['url_send_form'] = url('recepcion/documentos/manager');

		$data['form'] = view('Recepcion.formNuevaRecepcion') -> with($data);

		unset($data['tipos_documentos']);

		return view('Recepcion.nuevaRecepcion') -> with($data);

	}

	// Método para guardar un documento en estado de captura pendiente, o sea, aun no recepcionado completamente
	public function capturarRecepcion( $request )
	{

	}

	// Método para realizar el guardado y la recepción de un documento
	public function nuevaRecepcion( $request )
	{
		try {
			// Reemplazamos los saltos de línea, por "\n"
			$anexos = preg_replace('/\r|\n/','\n',$request -> anexos);
			// Reemplazamos las "\n" repetidas
			$anexos = str_replace('\n\n','\n',$anexos);

			DB::beginTransaction();

			// Guardamos los detalles del documento
			$detalle = new MDetalle;
			$detalle -> DETA_MUNICIPIO              = $request -> municipio;
			$detalle -> DETA_FECHA_RECEPCION        = $request -> recepcion;
			$detalle -> DETA_DESCRIPCION            = $request -> descripcion;
			$detalle -> DETA_RESPONSABLE            = $request -> responsable;
			$detalle -> DETA_ANEXOS                 = $anexos;
			$detalle -> DETA_OBSERVACIONES          = $request -> observaciones;
			$detalle -> DETA_ENTREGO_NOMBRE         = $request -> nombre;
			$detalle -> DETA_ENTREGO_EMAIL          = $request -> e_mail;
			$detalle -> DETA_ENTREGO_TELEFONO       = $request -> telefono;
			$detalle -> DETA_ENTREGO_IDENTIFICACION = $request -> identificacion;
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
			$seguimiento -> SEGU_DIRECCION_ORIGEN     = config_var('Sistema.Direcc.Origen');  // Dirección de la recepción, por default
			$seguimiento -> SEGU_DEPARTAMENTO_ORIGEN  = config_var('Sistema.Depto.Origen');   // Departamento de la recepción, por default
			$seguimiento -> SEGU_DIRECCION_DESTINO    = config_var('Sistema.Direcc.Destino'); // Dirección del procurador, por default
			$seguimiento -> SEGU_DEPARTAMENTO_DESTINO = config_var('Sistema.Depto.Destino');  // Departamento del procurador, por default
			$seguimiento -> SEGU_ESTADO_DOCUMENTO     = 1; // Documento recepcionado. Estado de documento por default
			$seguimiento -> save();
			
			if ($request -> tipo_documento == 1) // Si el tipo de documento es denuncia ...
			{
				$denuncia = new MDenuncia; // ... crear el registro de la denuncia
				$denuncia -> DENU_DOCUMENTO = $documento -> getKey();
				$denuncia -> save();
				$redirect = '?view=denuncias';
			}
			else if ( $request -> tipo_documento == 2 ) // Si el tipo de documento es un documento para denuncia ...
			{
				$denuncia = MDenuncia::with('Documento') -> find( $request -> denuncia );

				$documentoDenuncia = new MDocumentoDenuncia; // ... registramos el documento a la denuncia
				$documentoDenuncia -> DODE_DENUNCIA          = $denuncia -> getKey();
				$documentoDenuncia -> DODE_DOCUMENTO_ORIGEN  = $denuncia -> Documento -> getKey();
				$documentoDenuncia -> DODE_DOCUMENTO_LOCAL   = $documento -> getKey();
				$documentoDenuncia -> DODE_DETALLE           = $denuncia -> Documento -> Detalle -> getKey();
				$documentoDenuncia -> DODE_SEGUIMIENTO       = $denuncia -> Documento -> Seguimientos -> last() -> getKey();
				$documentoDenuncia -> save();

				$redirect = '?view=documentos-denuncias';
			}
			else
			{
				$redirect = '?view=documentos';
			}

			$redirect .= sprintf('&acuse=%d',$documento -> getKey()) ;

			// Guardamos los archivos o escaneos que se hayan agregado al archivo
			foreach ($request -> escaneos ?? [] as $escaneo) {
				$this -> nuevoEscaneo($documento, $escaneo,['escaneo_nombre'=>'A ver uno','escaneo_descripcion'=>'a ver dos']);
			}

			DB::commit();

			return redirect('recepcion/documentos/recepcionados' . $redirect);

		} catch(Exception $error) {
			DB::rollback();
			dd($error->getMessage());
		}

	}

	// Método para agregar un nuevo archivo a un documento
	public function nuevoEscaneo(MDocumento $documento, $file, $data)
	{
		$archivo = new MArchivo;
		$archivo -> ARCH_FOLDER   = 'storage/app/escaneos';
		$archivo -> ARCH_FILENAME = '';
		$archivo -> ARCH_PATH     = '';
		$archivo -> ARCH_TYPE     = $file -> extension();
		$archivo -> ARCH_MIME     = $file -> getMimeType();
		$archivo -> ARCH_SIZE     = $file -> getClientSize();

		$archivo -> save();

		$escaneo = new MEscaneo;
		$escaneo -> ESCA_ARCHIVO          = $archivo -> getKey(); 
		$escaneo -> ESCA_DOCUMENTO_LOCAL  = $documento -> getKey(); 
		$escaneo -> ESCA_NOMBRE           = $data['escaneo_nombre']; 
		$escaneo -> ESCA_DESCRIPCION      = $data['escaneo_descripcion']; 
		$escaneo -> save();

		$filename = sprintf('docto_%d_scan_%d_arch_%d_%s.pdf',$documento -> getKey(), $escaneo -> getKey(), $archivo -> getKey(), time());
		
		$file -> storeAs('',$filename,'escaneos');
		
		$archivo -> ARCH_FILENAME = $filename;
		$archivo -> ARCH_PATH     = 'storage/app/escaneos/' . $filename;
		$archivo -> save();
	}


	public function verRecepcion( $request )
	{

		$documento = MDocumento::find( $request -> id );

		dd($documento);

		return view('Recepcion.verDocumento')->with($data);

	}


}