<?php

namespace App\Http\Controllers\Configuracion\Catalogo;

use App\Http\Requests\ManagerEstadoDocumentoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\EstadosDocumentosDataTable;

/* Models */
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MEstadoDocumento;


class EstadoDocumentoController extends BaseController {

	private $form_id = 'form-estado-documento';

	public function index(EstadosDocumentosDataTable $dataTables){

		$data['table']    = $dataTables;
		$data['form_id']  = $this->form_id;
		$data['form_url'] = url('configuracion/catalogos/estados-documentos/nuevo');

		return view('Configuracion.Catalogo.EstadoDocumento.indexEstadoDocumento')->with($data);
	}

	public function manager(ManagerEstadoDocumentoRequest $request){

		$action = Input::get('action');

		switch ($action) {
			case 1: // Nuevo
				$response = $this->nuevoEstadoDocumento();
				break;
			case 2: // Editar
				$response = $this->editarEstadoDocumento();
				break;
			case 3: // Activar / Desactivar
				$response = $this->activarEstadoDocumento();
				break;
			case 4: // Eliminar
				$response = $this->eliminarEstadoDocumento();
				break;
			default:
				return response()->json(['message'=>'Petición no válida'],404);
				break;
		}
		return $response;
	}

	public function postDataTable(EstadosDocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function formNuevoEstadoDocumento(){
		try{

			$data = [];

			$data['title']         = 'Nuevo estado de documento';
			$data['url_send_form'] = url('configuracion/catalogos/estados-documentos/manager');
			$data['form_id']       = $this->form_id;
			$data['modelo']		   = null;
			$data['action']		   = 1;
			$data['id']		       = null;

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

			return view('Configuracion.Catalogo.EstadoDocumento.formEstadoDocumento')->with($data);

		}catch(Exception $error){

		}
	}

	public function nuevoEstadoDocumento(){
		try{

			$departamento = Input::get('departamento');

			if( $departamento == 0 )
				$departamento = null;

			$estadoDocumento = new MEstadoDocumento;
			$estadoDocumento->ESDO_NOMBRE       = Input::get('nombre');
			$estadoDocumento->ESDO_DIRECCION    = Input::get('direccion');
			$estadoDocumento->ESDO_DEPARTAMENTO = $departamento;
			$estadoDocumento->save();			

			$tables = ['dataTableBuilder',null,true];

			return response()->json(['status'=>true,'message'=>'El estado de documento se creó correctamente','tables' =>$tables]);
		
		}catch(Exception $error){

		}
	}

	public function formEditarEstadoDocumento(){
		try{
			$data['title']         = 'Editar estado de documento';
			$data['url_send_form'] = url('configuracion/catalogos/estados-documentos/manager');
			$data['form_id']       = $this->form_id;
			$data['modelo']		   = MEstadoDocumento::find( Input::get('id') );
			$data['action']		   = 2;
			$data['id']		       = Input::get('id');

			$direcciones = MDireccion::with('departamentos')
									->select('DIRE_DIRECCION','DIRE_NOMBRE')
									->where('DIRE_ENABLED',1)
									->orderBy('DIRE_NOMBRE')
									->get();

			$data['departamentos'] = [];

			foreach ($direcciones as $direccion) {

				$nombre_direccion = $direccion->DIRE_NOMBRE;
				foreach($direccion->departamentos as $departamento){
					$id_departamento      = $departamento->DEPA_DEPARTAMENTO;
					$nombre_departamento  = $departamento->DEPA_NOMBRE;
					$data['departamentos'][ $nombre_direccion ][ $id_departamento ] = $nombre_departamento;
				}

			}

			return view('Configuracion.Catalogo.EstadoDocumento.formEstadoDocumento')->with($data);

		}catch(Exception $error){

		}
	}

	public function editarEstadoDocumento(){
		try{
			$estadoDocumento = MEstadoDocumento::where('ESDO_DELETED',0)->find( Input::get('id') );
			$estadoDocumento->ESDO_NOMBRE       = Input::get('nombre');
			$estadoDocumento->ESDO_DEPARTAMENTO = Input::get('departamento');
			$estadoDocumento->save();			

			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'Los cambios se guardaron correctamente','tables' =>$tables]);

		}catch(Exception $error){
			
		}
	}

	public function activarEstadoDocumento(){
		try{
			$estadoDocumento = MEstadoDocumento::find( Input::get('id') );
			
			if( $estadoDocumento->ESDO_ENABLED == 1 ){
				$estadoDocumento->ESDO_ENABLED = 0;
				$message = 'El estado de documento se desactivó correctamente';
			}else{
				$estadoDocumento->ESDO_ENABLED = 1;
				$message = 'El estado de documento se activó correctamente';
			}
			$estadoDocumento->save();

			return response()->json(['status'=>true,'message'=>$message]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al guardar los cambios. Error ' . $error->getCode() ]);
		}
	}


	public function eliminarEstadoDocumento(){
		try{
			$estadoDocumento = MEstadoDocumento::find( Input::get('id') );
			
			$estadoDocumento->ESDO_DELETED    = 1;
			$estadoDocumento->ESDO_DELETED_AT = Carbon::now();
			$estadoDocumento->save();

			// Lista de tablas que se van a recargar automáticamente
			$tables = 'dataTableBuilder';

			return response()->json(['status'=>true,'message'=>'El estado de documento se eliminó correctamente','tables'=>$tables]);
		}catch(Exception $error){
			return response()->json(['status'=>false,'message'=>'Ocurrió un error al eliminar el estado de documento. Error ' . $error->getMessage() ]);
		}
	}

}