<?php
namespace App\Http\Controllers\Configuracion\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\System\MSystemTipoDocumento;

class SystemBitacoraController extends BaseController
{
	private $form_id;

	public function __construct(){
		$this -> form_id = 'form-tipo-documento';
	}

	public function index(){

		$data['form_id']  = $this -> form_id;
		$data['form_url'] = url('configuracion/sistema/tipos-documentos/nuevo');

		return view('Configuracion.Sistema.Bitacora.indexBitacora') -> with($data);
	}

	

}