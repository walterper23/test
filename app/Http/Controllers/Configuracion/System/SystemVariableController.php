<?php
namespace App\Http\Controllers\Configuracion\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\System\MSystemTipoDocumento;

/**
 * Controlador para administrar las variables globales que se manejan en el sistema.
 * Se trata de variables con valores únicos dentro del sistema que permiten controlar ciertos procesos.
 */
class SystemVariableController extends BaseController
{
	/**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this -> setLog('SystemVariableController.log');
    }

    /**
     * Método para retornar la página principal para la gestión de las variables del sistema
     */
	public function index(){

		return view('Configuracion.Sistema.Variables.indexVariables') -> with($data);
	}

}