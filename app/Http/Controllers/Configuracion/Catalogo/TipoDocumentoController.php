<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
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

		$data['table']   = $dataTables;
		$data['form_id'] = $this->form_id;

		return view('Configuracion.Catalogo.TipoDocumento.indexTipoDocumento')->with($data);
	}

	public function postDataTable(TiposDocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formTipoDocumento(){
		try{

			$data['title'] = 'Nuevo tipo de documento';
			$data['form_id'] = $this->form_id;
			$data['url_send_form'] = url('configuracion/catalogos/tipos-documentos/post-nuevo');
			$data['model'] = null;
			$data['id'] = null;
			
			return view('Configuracion.Catalogo.TipoDocumento.formTipoDocumento')->with($data);
		}catch(Exception $error){

		}
	}

	public function postNuevoTipoDocumento(){
		try{
			$data = Input::all();

			$validar = Validator::make($data,$this->getRules(),$this->getMessages());

			if( !$validar->passes() ){
				return response()->json(['status'=>false,'errors'=>$validar->errors()->toArray()]);
			}

			$tipoDocumento = new MTipoDocumento;
			$tipoDocumento->TIDO_NOMBRE_TIPO = $data['nombre'];
			$tipoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = ['dataTableBuilder'];

			return response()->json(['status'=>true,'message'=>'El tipo de documento se creó correctamente','tables'=>$tables]);
		
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al crear el tipo de documento. Error ' . $error->getMessage() ]);
		}
	}

	public function formEditarTipoDocumento(){
		try{

			$data['title'] = 'Editar tipo de documento';
			$data['form_id'] = $this->form_id;
			$data['url_send_form'] = url('configuracion/catalogos/tipos-documentos/post-editar');
			
			$data['model'] = MTipoDocumento::find( Input::get('id') );
			$data['id'] = Input::get('id');

			return view('Configuracion.Catalogo.TipoDocumento.formTipoDocumento')->with($data);
		}catch(Exception $error){

		}
	}

	public function postEditarTipoDocumento(){
		try{
			$data = Input::all();

			$rules = $this->getRules() + ['id' => 'deleted:cat_tipos_documentos,TIDO_TIPO_DOCUMENTO,TIDO_DELETED'];
			
			$messages = $this->getMessages() + [ 'id.deleted' => 'El tipo de documento no existe' ];

			$validar = Validator::make($data,$rules,$messages);

			if( !$validar->passes() ){
				return response()->json(['status'=>false,'errors'=>$validar->errors()->toArray()]);
			}

			$tipoDocumento = MTipoDocumento::findOrFail( $data['id'] );
			$tipoDocumento->TIDO_NOMBRE_TIPO = $data['nombre'];
			$tipoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = ['dataTableBuilder'];

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente','tables'=>$tables]);

		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al editar el tipo de documento. Error ' . $error->getMessage() ]);
		}
	}

	public function desactivarTipoDocumento(){
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
			$tables = ['dataTableBuilder'];

			return response()->json(['status'=>true,'message'=>$message,'tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el tipo de documento. Error ' . $error->getCode() ]);
		}


	}

	public function eliminarTipoDocumento(){
		try{
			$tipoDocumento = MTipoDocumento::where('TIDO_TIPO_DOCUMENTO',Input::get('id') )
								->where('TIDO_ENABLED',1)->where('TIDO_DELETED',0)->limit(1)->first();
			
			$tipoDocumento->TIDO_ENABLED = 0;
			$tipoDocumento->TIDO_DELETED = 1;
			$tipoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = ['dataTableBuilder'];

			return response()->json(['status'=>true,'message'=>'El tipo de documento se eliminó correctamente','tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el tipo de documento. Error ' . $error->getMessage() ]);
		}


	}

	public function getRules(){
		return [
			'nombre' => 'required|min:1,max:255'
		];
	}

	public function getMessages(){
		return [
			'nombre.required' => 'Introduzca un nombre',
			'nombre.min'      => 'Mínimo :min caracter',
			'nombre.max'      => 'Máximo :max caracteres'
		];
	}

}
