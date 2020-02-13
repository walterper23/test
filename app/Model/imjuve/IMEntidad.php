<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;


class IMEntidad extends BaseModel
{
    protected $table       = 'c_entidades';
    protected $primaryKey  = 'ENTI_NOMBRE';
    protected $prefix      = 'ENTI_ID';

    /* Methods */
    public function getNombre()
    {
    	return $this->getAttribute('ENTI_NOMBRE');
    }   
    public function getCodigoEntidad()
    {
        return $this->getAttribute('ENTI_CODIGO');
    }
    public function getNombreAbr()
    {
        return $this->getAttribute('ENTI_NOMBRE_ABR');
    }

    public static function getSelect()
    {
        return self::pluck('ENTI_NOMBRE','ENTI_ID')->toArray();
    }
}