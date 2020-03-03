<?php

namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;
use App\Model\imjuve\IMTipoActividad;

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
    public function getTipo()
    {
        return $this->getAttribute('ACTI_TACT_ID');
    }


    /* Relationships */
    public function tipoAct(){
        return $this->belongsTo(IMTipoActividad::class,'ACTI_TACT_ID','TACT_ID');
    }
  
   
}
