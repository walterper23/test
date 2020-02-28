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
}