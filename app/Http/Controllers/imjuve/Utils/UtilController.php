<?php
namespace App\Http\Controllers\imjuve\Utils;
use Illuminate\Http\Request;
/* Controllers */
use App\Http\Controllers\BaseController;
/* Catalogos */
use App\Model\imjuve\IMMunicipio;
use App\Model\imjuve\IMLocalidad;
/**
 * Controlador para la gestión de los usuarios del sistema
 */
class UtilController extends BaseController
{
    /**
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @autor cp
     * @descrip Retorna un array para utilizar en un select de municipios
     * @date 27/02/2020
     * @version 1.0
     * @param Request $request
     * @return mixed
     */
    public function getMunicipios(Request $request){
        $entidad    = ($request->exists('entidad')?$request->entidad:null);
        $query      = IMMunicipio::getSelectDepend($entidad)->pluck('MUNI_ID','MUNI_NOMBRE');
        return response()->json( $query );

    }
    /**
     * @autor cp
     * @descrip Retorna un array para utilizar en un select de localidades
     * @date 27/02/2020
     * @version 1.0
     * @param Request $request
     * @return mixed
     */
    public function getLocalidades(Request $request){
        $entidad        = ($request->exists('entidad')?$request->entidad:null);
        $municipio      = ($request->exists('municipio')?$request->municipio:null);
        $query      = IMLocalidad::getSelectDepend($entidad, $municipio)->pluck('key_loc','nom_loc');
        return response()->json( $query );

    }

}