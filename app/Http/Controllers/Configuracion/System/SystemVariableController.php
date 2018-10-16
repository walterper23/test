<?php
namespace App\Http\Controllers\Configuracion\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\System\MSystemConfig;

/**
 * Controlador para administrar las variables globales que se manejan en el sistema.
 * Se trata de variables con valores únicos dentro del sistema que permiten controlar ciertos procesos.
 */
class SystemVariableController extends BaseController
{
    private $form_id = 'form-configuracion-variables';

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
	public function index()
    {

        // cache()->forget('System.Config.Variables');

        $data['form_id']       = $this->form_id;
        $data['url_send_form'] = url('configuracion/sistema/variables/manager');
        $data['var']           = cache('System.Config.Variables')->mapWithKeys(function($item){
            return [ $item->getKey() => $item ];
        });

		return view('Configuracion.Sistema.Variables.indexVariables') -> with($data);
	}

    /**
     * Método para administrar las peticiones que recibe el controlador
     */
    public function manager(Request $request)
    {
        switch ($request -> action) {
            case 1: // Guardar cambios de variables
                $response = $this -> guardarCambios( $request );
                break;
            default:
                return response() -> json(['message'=>'Petición no válida'],404);
                break;
        }

        return $response;
    }

    public function guardarCambios( $request )
    {
        try {

            $variables = MSystemConfig::all();

            foreach ([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15] as $key) {
                $variable = $variables->find($key);

                $variable->SYCO_VALOR = $request->get('var' . $key);
                $variable->save();
            }

            // Eliminando y reiniciando de nuevo las variables en el caché
            MSystemConfig::setAllVariables();

            return $this -> responseSuccessJSON('todo bien');

        } catch(Exception $error) {

        }






    }

}