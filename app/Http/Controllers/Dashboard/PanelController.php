<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerUsuarioRequest;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\UsuariosDataTable;

/* Models */
use App\Model\MUsuario;


class PanelController extends BaseController {

	public function __construct(){

	}

	public function index(){

		$type = Input::get('type','all');

		$asignaciones = \Auth::user()->usuarioAsignaciones;

		$direcciones = \Auth::user()->direcciones;
		$departamentos = \Auth::user()->departamentos;

		$documentos = [
			'nuevos'      => [],
			'todos'       => [],
			'importantes' => [],
			'finalizados' => [],
		];

		// El usuario sólo puede ver documentos de ciertos departamentos que tiene asignado
		if( $departamentos->count() > 0 ){

			foreach ($departamentos as $departamento) {

				foreach ($departamento->seguimientos as $seguimiento) {
					
					$documento = $seguimiento->documento;

					$documentos['todos'][] = [
						'doc'         => $documento,
						'seguimiento' => $seguimiento
					];

				}

			}

		}else{ // El usuario puede ver documentos de todos los departamentos de la dirección

			foreach ($direcciones as $direccion) {

				foreach ($direccion->seguimientos as $seguimiento) {
					$documento = $seguimiento->documento;
					$documentos['todos'][] = [
						'doc'         => $documento,
						'seguimiento' => $seguimiento
					];
				}

			}
			
		}

		$data['conteoDocumentos'] = view('Panel.Documentos.conteoDocumentos')->with('type',$type)->with($documentos);

		switch ($type) {
			case 'news':
				$data['documentos'] = $documentos['nuevos'];
				
				break;
			case 'all':
				$data['documentos'] = $documentos['todos'];
				
				break;
			case 'important':
				$data['documentos'] = $documentos['importantes'];
				
				break;
			case 'finished':
				$data['documentos'] = $documentos['finished'];
				
				break;
			
			default:
				$data['documentos'] = $documentos['todos'];
				break;
		}

		return view('Panel.Documentos.index')->with($data);

	}



	public function nuevosDocumentos(){

	}

	public function todosDocumentos(){
		$documentos = MSeguimiento::where()->get();
	}

	public function importantesDocumentos(){

	}

	public function finalizadosDocumentos(){

	}

}
