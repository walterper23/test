<?php
namespace App\Http\Controllers\Configuracion\System;

use Illuminate\Http\Request;
use DB;

/* Controllers */
use App\Http\Controllers\BaseController;

/* Models */
use App\Model\Catalogo\MDireccion;
use App\Model\Catalogo\MDepartamento;
use App\Model\Catalogo\MEstadoDocumento;
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


        $data['direcciones'] = MDireccion::select('DIRE_DIRECCION','DIRE_NOMBRE')
                                    -> existente()
                                    -> orderBy('DIRE_NOMBRE')
                                    -> pluck('DIRE_NOMBRE','DIRE_DIRECCION')
                                    -> toArray();

        $data['departamentos'] = MDepartamento::select('DEPA_DEPARTAMENTO','DEPA_NOMBRE')
                                    -> existente()
                                    -> orderBy('DEPA_NOMBRE')
                                    -> pluck('DEPA_NOMBRE','DEPA_DEPARTAMENTO')
                                    -> toArray();

        $data['estados_documentos'] = MEstadoDocumento::select('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO')
                                    -> Disponible()
                                    -> orderBy('ESDO_NOMBRE')
                                    -> pluck('ESDO_NOMBRE','ESDO_ESTADO_DOCUMENTO')
                                    -> toArray();

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

            DB::beginTransaction();

            $variables = MSystemConfig::all();

            $variable = $variables->find(1);
            $variable->SYCO_VALOR = $request->get('var1',1);
            $variable->save();

            $variable = $variables->find(2);
            $variable->SYCO_VALOR = $request->get('var2',1);
            $variable->save();

            $variable = $variables->find(3);
            $variable->SYCO_VALOR = $request->get('var3',1);
            $variable->save();

            $variable = $variables->find(4);
            $variable->SYCO_VALOR = $request->get('var4',1);
            $variable->save();

            $variable = $variables->find(5);
            $variable->SYCO_VALOR = $request->get('var5',1);
            $variable->save();

            $variable = $variables->find(6);
            $variable->SYCO_VALOR = $request->get('var6',1);
            $variable->save();

            $variable = $variables->find(7);
            $variable->SYCO_VALOR = $request->get('var7',1);
            $variable->save();

            foreach ([1,2,3,4,5,6,7,8,9,10,11,12,13,14] as $key) {
                $variable = $variables->find($key);

                $variable->SYCO_VALOR = $request->get('var' . $key);
                $variable->save();
            }

            DB::commit();

            // Eliminando y reiniciando de nuevo las variables en el caché
            MSystemConfig::setAllVariables();

            $message = '<i class="fa fa-fw fa-cogs"></i> Cambios guardados correctamente';

            return $this->responseSuccessJSON($message);
        } catch(\Exception $error) {
            DB::rollback();

            $message = '<i class="fa fa-fw fa-warning"></i> No se pudieron guardar los cambios';

            return $this->responseDangerJSON($message);
        }






    }

}