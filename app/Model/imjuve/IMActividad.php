<?php

namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;

/* Presenter */
//use App\Presenters\MDepartamentoPresenter;

class IMActividad extends baseModel
{
    protected $table        = 'c_actividades';
    protected $primaryKey   = 'ACTI_ID';
    protected $prefix       = 'ACTI';

    public function getNombre()
    {
        return $this->getAttribute('ACTI_NOMBRE');
    }

    public function getDescripcion()
    {
        return $this->getAttribute('ACTI_DESCRIPCION');
    }

    /* Relationships */
   
}
