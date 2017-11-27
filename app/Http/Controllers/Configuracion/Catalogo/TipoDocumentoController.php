<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerTipoDocumentoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;
use Exception;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\TiposDocumentosDataTable;

/* Models */
use App\Model\Catalogo\MTipoDocumento;

class TipoDocumentoController extends BaseController{

	private $form_id;

	public function __construct(){
		$this->form_id = 'form-tipo-documento';
	}

	public function index(TiposDocumentosDataTable $dataTables){

		$data['table']    = $dataTables;
		$data['form_id']  = $this->form_id;
		$data['form_url'] = url('configuracion/catalogos/tipos-documentos/nuevo');

		return view('Configuracion.Catalogo.TipoDocumento.indexTipoDocumento')->with($data);
	}

	public function manager(ManagerTipoDocumentoRequest $request){

		$action = Input::get('action');

		switch ($action) {
			case 1: // Nuevo
				$response = $this->nuevoTipoDocumento();
				break;
			case 2: // Editar
				$response = $this->editarTipoDocumento();
				break;
			case 3: // Activar / Desactivar
				$response = $this->activarTipoDocumento();
				break;
			case 4: // Eliminar
				$response = $this->eliminarTipoDocumento();
				break;
			case 5: // Validar tipo de documento
				$response = $this->validarTipoDocumento();
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(TiposDocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formNuevoTipoDocumento(){
		try{

			$data = [
				'title'         => 'Nuevo tipo de documento',
				'form_id'       => $this->form_id,
				'url_send_form' => url('configuracion/catalogos/tipos-documentos/manager'),
				'action'        => 1,
				'model'         => null,
				'id'            => null
			];
			
			return view('Configuracion.Catalogo.TipoDocumento.formTipoDocumento')->with($data);
		}catch(Exception $error){

		}
	}

	public function nuevoTipoDocumento(){
		try{

			$tipoDocumento = new MTipoDocumento;
			$tipoDocumento->TIDO_NOMBRE_TIPO = Input::get('nombre');
			$tipoDocumento->TIDO_CREATED_AT  = Carbon::now();
			$tipoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = ['dataTableBuilder',null,true];

			return response()->json(['status'=>true,'message'=>'El tipo de documento se creó correctamente','tables'=>$tables]);
		
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al crear el tipo de documento. Error ' . $error->getMessage() ]);
		}
	}

	public function formEditarTipoDocumento(){
		try{

			$data['title'] = 'Editar tipo de documento';
			$data['form_id'] = $this->form_id;
			$data['url_send_form'] = url('configuracion/catalogos/tipos-documentos/manager');
			$data['action'] = 2;
			$data['model'] = MTipoDocumento::find( Input::get('id') );
			$data['id'] = Input::get('id');

			return view('Configuracion.Catalogo.TipoDocumento.formTipoDocumento')->with($data);
		}catch(Exception $error){

		}
	}

	public function editarTipoDocumento(){
		try{

			$tipoDocumento = MTipoDocumento::findOrFail( Input::get('id') );
			$tipoDocumento->TIDO_NOMBRE_TIPO = Input::get('nombre');
			$tipoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente','tables'=>$tables]);

		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al editar el tipo de documento. Error ' . $error->getMessage() ]);
		}
	}

	public function activarTipoDocumento(){
		try{
			$tipoDocumento = MTipoDocumento::where('TIDO_TIPO_DOCUMENTO',Input::get('id') )
								->where('TIDO_DELETED',0)->limit(1)->first();
			
			if( $tipoDocumento->TIDO_ENABLED == 1 ){
				$tipoDocumento->TIDO_ENABLED = 0;
				$message = 'El tipo de documento se desactivó correctamente';
			}else{
				$tipoDocumento->TIDO_ENABLED = 1;
				$message = 'El tipo de documento se activó correctamente';
			}
			$tipoDocumento->save();

			return response()->json(['status'=>true,'message'=>$message]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}

	public function eliminarTipoDocumento(){
		try{
			$tipoDocumento = MTipoDocumento::where('TIDO_TIPO_DOCUMENTO',Input::get('id'))
								->where('TIDO_DELETED',0)->limit(1)->first();
			
			$tipoDocumento->TIDO_ENABLED    = 0;
			$tipoDocumento->TIDO_DELETED    = 1;
			$tipoDocumento->TIDO_DELETED_AT = Carbon::now();
			$tipoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'El tipo de documento se eliminó correctamente','tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el tipo de documento. Error ' . $error->getMessage() ]);
		}

	}

	public function validarTipoDocumento(){
		try{
			$tipoDocumento = MTipoDocumento::where('TIDO_TIPO_DOCUMENTO',Input::get('id'))
								->where('TIDO_DELETED',0)->limit(1)->first();
			
			$tipoDocumento->TIDO_VALIDAR = $tipoDocumento->TIDO_VALIDAR * -1 + 1;
			$tipoDocumento->save();

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente']);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getMessage() ]);
		}


	}

}
