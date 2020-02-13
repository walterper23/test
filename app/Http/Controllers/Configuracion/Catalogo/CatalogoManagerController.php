<?php
namespace App\Http\Controllers\Configuracion\Catalogo;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\Catalogo\MAnexo;
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MEstadoDocumento;
use App\Model\Catalogo\MPuesto;
use App\Model\MUsuario;

use App\Model\System\MSystemTipoDocumento;
use App\Model\System\MSystemEstadoDocumento;
use App\Model\System\MSystemConfig;

/**
 * Controlador para crear un acceso a los catálogos del sistema
 */
class CatalogoManagerController extends BaseController
{	
	/**
	 * Método para retornar la vista principal con el acceso a los catálogos del sistema
	 */
	public function index(){

		$data = [
			'anexos'                  => MAnexo::siExistenteDisponible()->count(),
			'departamentos'           => MDepartamento::siExistenteDisponible()->count(),
			'direcciones'             => MDireccion::siExistenteDisponible()->count(),
			'puestos'                 => MPuesto::siExistenteDisponible()->count(),
			'estadosDocumentos'       => MEstadoDocumento::siExistenteDisponible()->count(),
			'usuarios'                => MUsuario::siExistenteDisponible()->count(),
			
			'systemTiposDocumentos'   => MSystemTipoDocumento::getAllTiposDocumentos()->count(),
			'systemEstadosDocumentos' => MSystemEstadoDocumento::getAllEstadosDocumentos()->count(),
			'systemConfigVariables'   => MSystemConfig::getAllVariables()->count(),
		];

		return view('Configuracion.Catalogo.index')->with($data);
	}

}