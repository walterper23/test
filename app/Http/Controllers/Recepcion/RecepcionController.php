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
use App\Model\Catalogo\MTipoDocumento;
use App\Model\Catalogo\MAnexo;

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

}
