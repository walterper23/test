<?php
namespace App\Http\Controllers\Configuracion\Sistema;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerTipoDocumentoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\SistemaTiposDocumentosDataTable;

/* Models */
use App\Model\Sistema\MSistemaTipoDocumento;

class SistemaTipoDocumentoController extends BaseController {

	private $form_id;

	public function __construct(){
		$this -> form_id = 'form-tipo-documento';
	}

	public function index(SistemaTiposDocumentosDataTable $dataTables){

		$data['table']    = $dataTables;
		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/sistema/tipos-documentos/nuevo');

		return view('Configuracion.Sistema.TipoDocumento.indexTipoDocumento') -> with($data);
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

	public function postDataTable(SistemaTiposDocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formEditarTipoDocumento(){
		try{

			$data['title']         = 'Editar tipo de documento';
			$data['form_id']       = $this -> form_id;
			$data['url_send_form'] = url('configuracion/sistema/tipos-documentos/manager');
			$data['action']        = 2;
			$data['model']         = MSistemaTipoDocumento::find( Input::get('id') );
			$data['id']            = Input::get('id');

			return view('Configuracion.Sistema.TipoDocumento.formTipoDocumento') -> with($data);
		}catch(Exception $error){

		}
	}

	public function editarTipoDocumento( $request ){
		try{

			$tipoDocumento = MSistemaTipoDocumento::findOrFail( $request -> id );
			$tipoDocumento -> SYTD_NOMBRE_TIPO = $request -> nombre;
			$tipoDocumento -> save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			$message = sprintf('<i class="fa fa-fw fa-check"></i> Tipo de documento <b>%s</b> modificado',$tipoDocumento -> getCodigo());
			return $this -> responseSuccessJSON($message,$tables);

		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al editar el tipo de documento. Error ' . $error->getMessage() ]);
		}
	}

	public function activarTipoDocumento( $request ){
		try{
			$tipoDocumento = MSistemaTipoDocumento::find( $request -> id );
			
			$tipoDocumento -> cambiarDisponibilidad() -> save();

			if( $tipoDocumento -> disponible() ){
				$message = sprintf('<i class="fa fa-fw fa-check"></i> Tipo de documento <b>%s</b> activado',$tipoDocumento -> getCodigo());
				return $this -> responseInfoJSON($message);
			}else{
				$message = sprintf('<i class="fa fa-fw fa-warning"></i> Tipo de documento <b>%s</b> desactivado',$tipoDocumento -> getCodigo());
				return $this -> responseWarningJSON($message);
			}

		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	public function validarTipoDocumento(){
		try{
			$tipoDocumento = MSistemaTipoDocumento::find( $request -> id );
			
			$tipoDocumento -> SYTD_VALIDAR = $tipoDocumento -> SYTD_VALIDAR * -1 + 1;
			$tipoDocumento -> save();

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente']);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() ]);
		}


	}

}