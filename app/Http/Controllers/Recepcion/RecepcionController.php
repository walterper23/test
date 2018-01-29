<?php

namespace App\Http\Controllers\Recepcion;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerAnexoRequest;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\DocumentosDataTable;

/* Models */
use App\Model\MDocumento;
use App\Model\MSeguimiento;
use App\Model\Catalogo\MEstadoDocumento;
use App\Model\Catalogo\MTipoDocumento;
use App\Model\Catalogo\MDireccion;

class RecepcionController extends BaseController{

	public function __construct(){

	}

	public function index(DocumentosDataTable $dataTables){
		return view('Recepcion.indexRecepcion')->with('table', $dataTables);
	}

	public function postDataTable(DocumentosDataTable $dataTables){
		return $dataTables->getData();
	}

	public function nuevaRecepcion(){

		$data = [];

		$data['tipos_documentos'] = MTipoDocumento::select('TIDO_TIPO_DOCUMENTO','TIDO_NOMBRE_TIPO')
											->where('TIDO_ENABLED',1)
											->where('TIDO_DELETED',0)
											->orderBy('TIDO_NOMBRE_TIPO')
											->pluck('TIDO_NOMBRE_TIPO','TIDO_TIPO_DOCUMENTO')
											->toArray();

		$data['anexos'] = MAnexo::select('ANEX_ANEXO','ANEX_NOMBRE')
									->where('ANEX_ENABLED',1)
									->where('ANEX_DELETED',0)
									->orderBy('ANEX_NOMBRE')
									->pluck('ANEX_NOMBRE','ANEX_ANEXO')
									->toArray();

		$data['context'] = 'context-form-recepcion';
		$data['form_id'] = 'form-recepcion';

		$data['form'] = view('Recepcion.formNuevaRecepcion')->with($data);

		unset($data['tipos_documentos']);

		return view('Recepcion.nuevaRecepcion')->with($data);

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
