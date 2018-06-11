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
			'anexos'            => MAnexo::existenteDisponible() -> count(),
			'departamentos'     => MDepartamento::existenteDisponible() -> count(),
			'direcciones'       => MDireccion::existenteDisponible() -> count(),
			'puestos'           => MPuesto::existenteDisponible() -> count(),
			'estadosDocumentos' => MEstadoDocumento::existenteDisponible() -> count(),
			'usuarios'          => MUsuario::existenteDisponible() -> count(),
		];

		return view('Configuracion.Catalogo.index')->with($data);
	}

}