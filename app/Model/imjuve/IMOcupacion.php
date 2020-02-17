<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;


class IMOcupacion extends BaseModel
{
    protected $table       = 'c_ocupaciones';
    protected $primaryKey  = 'OCUP_ID';
    protected $prefix      = 'OCUP';

    /* Methods */
    public function getNombre()
    {
    	return $this->getAttribute('OCUP_NOMBRE');
    }

    public static function getSelect()
    {
        return self::pluck('OCUP_NOMBRE','OCUP_ID')->toArray();
    }
}