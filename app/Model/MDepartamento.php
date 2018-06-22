<?php
namespace App\Model\Catalogo;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MDepartamentoPresenter;

class MDepartamento extends BaseModel
{
    protected $table        = 'cat_departamentos';
    protected $primaryKey   = 'DEPA_DEPARTAMENTO';
    protected $prefix       = 'DEPA';

    public function getNombre()
    {
        return $this -> getAttribute('DEPA_NOMBRE');
    }

    public function getDireccion()
    {
        return $this -> getAttribute('DEPA_DIRECCION');
    }

    /* Relationships */

    public function Direccion()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','DEPA_DIRECCION','DIRE_DIRECCION');
    }

    public function Seguimientos()
    {
        return $this -> hasMany('App\Model\MSeguimiento','SEGU_DEPARTAMENTO',$this -> getKeyName());
    }

    public function Puestos()
    {
        return $this -> hasMany('App\Model\Catalogo\MPuesto','PUES_DEPARTAMENTO',$this -> getKeyName());
    }

    /* Presenter */
    public function presenter()
    {
    	return new MDepartamentoPresenter($this);
    }

}