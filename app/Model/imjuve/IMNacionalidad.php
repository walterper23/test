<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;


class IMNacionalidad extends BaseModel
{
    protected $table       = 'c_nacionalidades';
    protected $primaryKey  = 'NACI_CODIGO';
    protected $prefix      = 'NACI';

    /* Methods */
    public function getNombre()
    {
    	return $this->getAttribute('NACI_NBOMBRE');
    }   
    public function getClave()
    {
        return $this->getAttribute('NACI_CLAVE');
    }

    public static function getSelect()
    {
        return self::pluck('NACI_NOMBRE','NACI_CODIGO')->toArray();
    }
}