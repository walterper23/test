<?php
namespace App\Http\Controllers\Panel;

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

		$asignaciones = \Auth::user() -> UsuarioAsignaciones;

		$direcciones = \Auth::user() -> Direcciones;
		$departamentos = \Auth::user() -> Departamentos;

		$documentos = [
			'nuevos'      => [],
			'todos'       => [],
			'importantes' => [],
			'archivados'  => [],
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

		$data['conteoDocumentos'] = view('Panel.Documentos.conteoDocumentos') -> with('type',$type) -> with($documentos);

		switch ($type) {
			case 'news':
				$data['title']      = 'Documentos nuevos';
				$data['documentos'] = $documentos['nuevos'];
				break;
			case 'all':
				$data['title']      = 'Documentos';
				$data['documentos'] = $documentos['todos'];
				break;
			case 'important':
				$data['title']      = 'Documentos importantes';
				$data['documentos'] = $documentos['importantes'];
				break;
			case 'archived':
				$data['title']      = 'Documentos archivados';
				$data['documentos'] = $documentos['archivados'];
				break;
			case 'finished':
				$data['title']      = 'Documentos finalizados';
				$data['documentos'] = $documentos['finalizados'];
				break;
			
			default:
				$data['title'] = 'Documentos nuevos';$data['documentos'] = $documentos['todos'];
				break;
		}


		return view('Panel.Documentos.index') -> with($data);

	}



	public function documentosNuevos(){

	}

	public function documentosTodos(){
		
	}

	public function documentosImportantes(){

	}

	public function documentosFinalizados(){

	}

}