<?php

namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;
use App\IMActividad;

/* Presenter */
//use App\Presenters\MDepartamentoPresenter;

class IMTipoActividad extends baseModel
{
    protected $table        = 'c_tipos_actividades';
    protected $primaryKey   = 'TACT_ID';
    protected $prefix       = 'TACT';

    public function getNombre()
    {
        return $this->getAttribute('TACT_NOMBRE');
    }

    public function getDescripcion()
    {
        return $this->getAttribute('TACT_DESCRIPCION');
    }

    public static function getSelect()
    {
        return self::pluck('TACT_NOMBRE','TACT_ID')->toArray();
    }

    public function actividad(){
        return $this->hasMany(IMActividad::class,'ACTI_TACT_ID','TACT_ID');
    }
  
    
}
