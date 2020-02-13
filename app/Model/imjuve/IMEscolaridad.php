<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;


class IMEscolaridad extends BaseModel
{
    protected $table       = 'c_escolaridades';
    protected $primaryKey  = 'ESCO_ID';
    protected $prefix      = 'ESCO';

    /* Methods */
    public function getNombre()
    {
    	return $this->getAttribute('ESCO_NOMBRE');
    }

    public function getEdad()
    {
        return $this->getAttribute('ESCO_EDAD');
    }


    public static function getSelect()
    {
        return self::pluck('ESCO_NOMBRE','ESCO_ID')->toArray();
    }
}