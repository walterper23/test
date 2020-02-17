<?php
namespace App\Model\imjuve;

/* Models */
use App\Model\BaseModel;

/* Presenter */
//use App\Presenters\MDepartamentoPresenter;

class IMAfiliacion extends BaseModel
{
    protected $table        = 'm_afiliados';
    protected $primaryKey   = 'AFIL_ID';
    protected $prefix       = 'AFIL';

    public function getNombre()
    {
        return $this->getAttribute('DEPA_NOMBRE');
    }

    public function getDireccion()
    {
        return $this->getAttribute('DEPA_DIRECCION');
    }

    /* Relationships */

    public function Direccion()
    {
        return $this->belongsTo('App\Model\Catalogo\MDireccion','DEPA_DIRECCION','DIRE_DIRECCION');
    }

    public function Seguimientos()
    {
        return $this->hasMany('App\Model\MSeguimiento','SEGU_DEPARTAMENTO',$this->getKeyName());
    }

    public function Puestos()
    {
        return $this->hasMany('App\Model\Catalogo\MPuesto','PUES_DEPARTAMENTO',$this->getKeyName());
    }

    /* Presenter */
    /*public function presenter()
    {
    	return new MDepartamentoPresenter($this);
    }*/

}