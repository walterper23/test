<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;


class IMEstadoCivil extends BaseModel
{
    protected $table       = 'c_estados_civiles';
    protected $primaryKey  = 'ESCI_ID';
    protected $prefix      = 'ESCI';

    /* Methods */
    public function getNombre()
    {
    	return $this->getAttribute('ESCI_NBOMBRE');
    }

    public static function getSelect()
    {
        return self::pluck('ESCI_NOMBRE','ESCI_ID')->toArray();
    }
}