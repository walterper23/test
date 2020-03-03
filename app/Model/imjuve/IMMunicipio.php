<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;


class IMMunicipio extends BaseModel
{
    protected $table       = 'c_municipios';
    protected $primaryKey  = 'MUNI_ID';
    protected $prefix      = 'MUNI';

    /* Methods */
    public function getNombre()
    {
        return $this->getAttribute('MUNI_NOMBRE');
    }
    public function getCodigoEntidad()
    {
        return $this->getAttribute('MUNI_CLAVE');
    }

    /**
     * @autor cp
     * @descrip Recive un ID entidad para retornar los municipios asociados
     * @date 27/02/2020
     * @version 1.0
     * @param null $entidad
     * @return mixed
     */
    public static  function getSelectDepend($entidad = null){
        return self::where(function($q) use ($entidad){
                    if(!is_null($entidad)){
                        $q->where('MUNI_ENTI_ID',$entidad);
                    }
                });
    }
}