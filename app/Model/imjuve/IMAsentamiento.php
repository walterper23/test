<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;

class IMAsentamiento extends BaseModel
{
    protected $table       = 'c_asentamientos';
    protected $primaryKey  = 'asentamiento_id';

    /* Methods */
    public function getNombre()
    {
        return $this->getAttribute('asentamiento_nombre');
    }

    /**
     * @autor cp
     * @descrip Recive un ID entidad, ID municipio, ID localidad para retornar los asentamientos asociados
     * @date 27/02/2020
     * @version 1.0
     * @param null $entidad
     * @param $municipio
     * @param $localidad
     * @return mixed
     */
    public static  function getSelectDepend($entidad = null,$municipio, $localidad){
        return self::where(function($q) use ($entidad, $municipio, $localidad){
            if(!is_null($entidad)){
                $q->where('entidad_id',$entidad);
            }
            if(!is_null($municipio)){
                $q->where('municipio_id',$municipio);
            }
            if(!is_null($localidad)){
                $q->where('localidad_id',$localidad);
            }
        });
    }
}