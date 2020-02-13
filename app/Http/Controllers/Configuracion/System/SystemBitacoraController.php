<?php
namespace App\Http\Controllers\Configuracion\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\System\MSystemTipoDocumento;

/**
 * Controlador para guardar la información de todos los eventos que ocurren en el sistema.
 */
class SystemBitacoraController extends BaseController
{
    private $form_id = 'form-tipo-documento';

    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this -> setLog('SystemBitacoraController.log');
    }

    /**
     * Método para retornar la página principal con la bitácora del sistema
     */
    public function index(){

        $data['form_id']  = $this -> form_id;
        $data['form_url'] = url('configuracion/sistema/tipos-documentos/nuevo');

        return view('Configuracion.Sistema.Bitacora.indexBitacora') -> with($data);
    }

}