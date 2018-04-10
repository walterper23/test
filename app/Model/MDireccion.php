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
        return $this -> hasMany('App\Model\Catalogo\MDepartamento','DEPA_DIRECCION',$this -> getKeyName());
    }

    public function DepartamentosExistentes()
    {
        return $this -> Departamentos() -> existente();
    }

    public function Puestos()
    {
        return $this -> hasMany('App\Model\Catalogo\MPuesto','PUES_DIRECCION',$this -> getKeyName());
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