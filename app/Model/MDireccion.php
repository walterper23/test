<?php
namespace App\Model\Catalogo;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MDireccionPresenter;

class MDireccion extends BaseModel
{
    protected $table        = 'cat_direcciones';
    protected $primaryKey   = 'DIRE_DIRECCION';
    protected $prefix       = 'DIRE';
    
    /* Methods */

    public function getNombre()
    {
        return $this -> attributes['DIRE_NOMBRE'];
    }

    /* Relationships */

    public function Departamentos()
    {
        return $this -> hasMany('App\Model\Catalogo\MDepartamento','DEPA_DIRECCION',$this -> getKeyName()) -> where('DEPA_DELETED',0);
    }

    public function Seguimientos()
    {
        return $this -> hasMany('App\Model\MSeguimiento','SEGU_DIRECCION',$this -> getKeyName());
    }

    /* Presenter */
    public function presenter()
    {
    	return new MDireccionPresenter($this);
    }    

}