<?php
namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use App\Http\Requests\RecepcionForaneaRequest;
use Illuminate\Filesystem\Filesystem;
use Carbon\Carbon;
use Exception;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Dashboard\NotificacionController;

/* Models */
use App\Model\MAcuseRecepcion;
use App\Model\MArchivo;
use App\Model\MDenuncia;
use App\Model\MDetalle;
use App\Model\MDocumentoForaneo;
use App\Model\MDocumentoDenuncia;
use App\Model\MEscaneo;
use App\Model\MMunicipio;
use App\Model\MSeguimiento;
use App\Model\Catalogo\MAnexo;
use App\Model\System\MSystemTipoDocumento;

/* DataTables */
use App\DataTables\DenunciasForaneasDataTable;
use App\DataTables\DocumentosDenunciasForaneasDataTable;
use App\DataTables\DocumentosForaneosDataTable;

class RecepcionForaneaController extends BaseController
{
	public function __construct()
	{
		parent::__construct();
		$this -> middleware('can:REC.VER.FORANEO',['only' => ['index','postDataTable']]);
		$this -> middleware('can:REC.DOCUMENTO.FORANEO',['only' => ['formNuevaRecepcion','manager']]);
		$this -> setLog('RecepcionForaneaController.log');
	}

	public function index(Request $request){

		$tabla1 = new DenunciasForaneasDataTable();
		$tabla2 = new DocumentosDenunciasForaneasDataTable();
		$tabla3 = new DocumentosForaneosDataTable();

		$data['table1'] = $tabla1;
		$data['table2'] = $tabla2;
		$data['table3'] = $tabla3;

		$view  = $request -> get('view','denuncias');
		$acuse = $request -> get('acuse');

		$data['tab_1'] = '';
		$data['tab_2'] = '';
		$data['tab_3'] = '';
		$data['tab_4'] = '';

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
			case 'recepciones-foraneas':
				$data['tab_4'] = ' active';
				break;
			default:
				$data['tab_1'] = ' active';
				break;
		}

		$data['acuse'] = $acuse;

		return view('Recepcion.indexRecepcionForanea') -> with($data);
	}

	public function manager(RecepcionForaneaRequest $request){

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
			case 5: // Enviar documento a Oficialía de Partes Local
				$response = $this -> enviarDocumento( $request );
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(Request $request){

		$type = $request -> get('type','denuncias');

		switch ($type) {
			case 'denuncias' :
				$dataTables = new DenunciasForaneasDataTable;
				break;
			case 'documentos-denuncias':
				$dataTables = new DocumentosDenunciasForaneasDataTable;
				break;
			case 'documentos':
				$dataTables  = new DocumentosForaneosDataTable;
				break;
			default:
				$dataTables  = new DocumentosForaneosDataTable;
				break;
		}

		return $dataTables -> getData();
	}

	public function formNuevaRecepcion(){

		$data = [];

		// Recuperamos los tipos de documentos que se pueden recepcionar
		$data['tipos_documentos'] = MSystemTipoDocumento::where('SYTD_ENABLED',1) -> get();

		user() -> Direcciones() -> with('Departamentos');

		$data['direcciones'] = user() -> Direcciones -> pluck('DIRE_NOMBRE','DIRE_DIRECCION') -> toArray();

		$data['departamentos'] = [];

		foreach (user() -> Direcciones as $direccion)
		{
			foreach ($direccion -> Departamentos as $departamento)
			{
				$data['departamentos'][] = [
					$direccion -> getKey(),
					$departamento -> getKey(),
					$departamento -> getNombre()
				];
			}
		}

		$data['denuncias'] = MDenuncia::select('DENU_DENUNCIA','DENU_NO_EXPEDIENTE')
							-> join('documentos','DENU_DOCUMENTO','=','DOCU_DOCUMENTO')
							-> whereNotNull('DENU_NO_EXPEDIENTE')
							-> whereIn('DOCU_SYSTEM_ESTADO_DOCTO',[2,3]) // Documento recepcionado, Documento en seguimiento
							-> pluck('DENU_NO_EXPEDIENTE','DENU_DENUNCIA')
							-> toArray();

		// Recuperamos los anexos almacenados en el sistema
		$data['anexos'] = MAnexo::existenteDisponible()	-> orderBy('ANEX_NOMBRE') -> pluck('ANEX_NOMBRE','ANEX_ANEXO') -> toArray();

		$data['municipios'] = MMunicipio::selectRaw('MUNI_MUNICIPIO AS id, CONCAT(MUNI_CLAVE," - ",MUNI_NOMBRE) AS nombre,MUNI_ENABLED') -> disponible() -> pluck('nombre','id') -> toArray();

		$data['context']       = 'context-form-recepcion-foranea';
		$data['form_id']       = 'form-recepcion-foranea';
		$data['url_send_form'] = url('recepcion/documentos-foraneos/manager');

		$data['form'] = view('Recepcion.formNuevaRecepcionForanea') -> with($data);

		unset($data['tipos_documentos']);

		return view('Recepcion.nuevaRecepcionForanea') -> with($data);

	}

	// Método para realizar el guardado y la recepción de un documento foráneo
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

			// Guardamos el documento foráneo
			$documento = new MDocumentoForaneo;
			$documento -> DOFO_SYSTEM_TIPO_DOCTO   = $request -> tipo_documento;
			$documento -> DOFO_DETALLE             = $detalle -> getKey();
			$documento -> DOFO_NUMERO_DOCUMENTO    = $request -> numero;
			$documento -> DOFO_SYSTEM_TRANSITO     = 0; // Documento foráneo aún No enviado a Oficialía de partes
			$documento -> DOFO_VALIDADO            = 0; // Documento foráneo aún no Validado por Oficialía de partes
			$documento -> DOFO_RECEPCIONADO        = 0; // Documento foráneo aún no Recepcionado por Oficialía de partes
			$documento -> save();

			/* !!! El documento foráneo no debe crear ningún primer seguimiento !!! */
			
			$nombre_acuse = sprintf('ARDF/%s/%s/%s/',date('Y'),date('m'),$documento -> getCodigo());

			if ($request -> tipo_documento == 1) // Si el tipo de documento es denuncia ...
			{
				/* !!! El documento foráneo no debe crear ningún registro de denuncia !!! */
				$nombre_acuse = sprintf('%sDOCTO/%s',$nombre_acuse,$documento -> getCodigo());
				$redirect = '?view=denuncias';
			}
			else if ( $request -> tipo_documento == 2 ) // Si el tipo de documento es un documento para denuncia ...
			{
				$denuncia = MDenuncia::with('Documento') -> find( $request -> denuncia );

				$documentoDenuncia = new MDocumentoDenuncia; // ... registramos el documento a la denuncia
				$documentoDenuncia -> DODE_DENUNCIA          = $denuncia -> getKey();
				$documentoDenuncia -> DODE_DOCUMENTO_ORIGEN  = $denuncia -> Documento -> getKey();
				$documentoDenuncia -> DODE_DOCUMENTO_FORANEO = $documento -> getKey();
				$documentoDenuncia -> DODE_DETALLE           = $denuncia -> Documento -> Detalle -> getKey();
				$documentoDenuncia -> DODE_SEGUIMIENTO       = $denuncia -> Documento -> Seguimientos -> last() -> getKey();
				$documentoDenuncia -> save();

				$redirect = '?view=documentos-denuncias';
				$nombre_acuse = sprintf('%sDOCTO/DENU/%s',$nombre_acuse,$documentoDenuncia -> getCodigo());
			}
			else
			{
				$redirect = '?view=documentos';
				$nombre_acuse = sprintf('%sDOCTO/%s',$nombre_acuse,$documento -> getCodigo());
			}
			
			// Creamos el registro del acuse de recepción del documento
			$acuse = new MAcuseRecepcion;
			$acuse -> ACUS_NUMERO    = $nombre_acuse;
			$acuse -> ACUS_NOMBRE    = sprintf('%s.pdf',str_replace('/','_', $nombre_acuse));
			$acuse -> ACUS_DOCUMENTO = $documento -> getKey();
			$acuse -> ACUS_CAPTURA   = 2; // Documento foráneo
			$acuse -> ACUS_DETALLE   = $detalle -> getKey();
			$acuse -> ACUS_USUARIO   = userKey();
			$acuse -> ACUS_ENTREGO   = $request -> nombre;
			$acuse -> ACUS_RECIBIO   = user() -> UsuarioDetalle -> presenter() -> nombreCompleto();
			$acuse -> save();

			// Lista de los nombres de los escaneos
			$escaneo_nombres = $request -> escaneo_nombre ?? [];

			// Guardamos los archivos o escaneos que se hayan agregado al archivo
			foreach ($request -> escaneo ?? [] as $key => $escaneo) {

				$nombre = $escaneo -> getClientOriginalName();
				if( isset($escaneo_nombres[$key]) && !empty(trim($escaneo_nombres[$key])) )
				{
					$nombre = trim($escaneo_nombres[$key]);
				}

				$this -> nuevoEscaneo($documento, $escaneo,['escaneo_nombre'=>$nombre]);
			}

			DB::commit();

			// Mandamos el correo de notificación a los usuarios que tengan la preferencia asignada
			$notificacion = new NotificacionController;
			$notificacion -> mandarNotificacionCorreo($documento);
			
			if ($request -> acuse) // Si el usuario ha indicado que quiere abrir inmediatamente el acuse de recepción
			{
				$url = url( sprintf('recepcion/acuse/documento/%s?d=0',$acuse -> getNombre()) );
				$request -> session() -> flash('urlAcuseAutomatico', $url);
			}

			return $this -> responseSuccessJSON(url('recepcion/documentos-foraneos/recepcionados' . $redirect));
		}catch(Exception $error){
			DB::rollback();
			dd($error->getMessage());
		}

	}

	// Método para agregar un nuevo archivo a un documento foráneo
	public function nuevoEscaneo(MDocumentoForaneo $documento, $file, $data)
	{
		$archivo = new MArchivo;
		$archivo -> ARCH_FOLDER   = 'app/escaneos';
		$archivo -> ARCH_FILENAME = '';
		$archivo -> ARCH_PATH     = '';
		$archivo -> ARCH_TYPE     = $file -> extension();
		$archivo -> ARCH_MIME     = $file -> getMimeType();
		$archivo -> ARCH_SIZE     = $file -> getClientSize();

		$archivo -> save();

		$escaneo = new MEscaneo;
		$escaneo -> ESCA_ARCHIVO            = $archivo -> getKey(); 
		$escaneo -> ESCA_DOCUMENTO_FORANEO  = $documento -> getKey(); 
		$escaneo -> ESCA_NOMBRE             = $data['escaneo_nombre']; 
		$escaneo -> save();

		$filename = sprintf('docto_foraneo_%d_scan_%d_arch_%d_%s.pdf',$documento -> getKey(), $escaneo -> getKey(), $archivo -> getKey(), time());
		
		$file -> storeAs('',$filename,'escaneos');
		
		$archivo -> ARCH_FILENAME = $filename;
		$archivo -> ARCH_PATH     = 'app/escaneos/' . $filename;
		$archivo -> save();
	}


	public function enviarDocumento( $request )
	{
		$documento = MDocumentoForaneo::find( $request -> id );
		$documento -> DOFO_SYSTEM_TRANSITO = 1;
		$documento -> DOFO_FECHA_ENVIADO   = Carbon::now();
		$documento -> save();

		$message = sprintf('Documento #<b>%s</b> enviado <i class="fa fa-fw fa-car"></i>',$documento -> getCodigo());

		if ($documento -> getTipoDocumento() == 1)
		{
			$tables = ['denuncias-datatable',null,true];
		}
		else if ($documento -> getTipoDocumento() == 2)
		{
			$tables = ['documentos-denuncias-datatable',null,true];
		}
		else
		{
			$tables = ['documentos-datatable',null,true];
		}

		return $this -> responseSuccessJSON($message,$tables);
	}

}