<?php
namespace App\Http\Controllers\Configuracion\Catalogo;

use Illuminate\Http\Request;
use App\Http\Requests\AnexoRequest;
use Illuminate\Support\Facades\Input;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */

/**
 * Controlador para ejucutar diversas peticiones extras de solicitud de información.
 */
class ExtraRequestController extends BaseController
{
    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
        $this -> setLog('ExtraRequestController.log');
    }

}