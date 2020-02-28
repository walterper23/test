<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;


class IMLocalidad extends BaseModel
{
    protected $table       = 'c_localidades';
    protected $primaryKey  = 'cve_loc';
    protected $prefix      = 'LOC';

    /* Methods */
    public function getNombre()
    {
        return $this->getAttribute('nom_loc');
    }
    public function getCodigoEntidad()
    {
        return $this->getAttribute('clave_loc');
    }
    /**
     * @autor cp
     * @descrip Recive un ID entidad y ID municipio para retornar las localidades asociados
     * @date 27/02/2020
     * @version 1.0
     * @param null $entidad
     * @return mixed
     */
    public static  function getSelectDepend($entidad = null, $municipio =null){
        return self::where(function($q) use ($entidad,$municipio){
            if(!is_null($entidad)){
                $q->where('cve_ent',$entidad);
            }
            if(!is_null($municipio)){
                $q->where('cve_mun',$municipio);
            }
        });
    }
}