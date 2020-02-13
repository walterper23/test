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
        return $this->getAttribute('DIRE_NOMBRE');
    }

    /* Relationships */

    public function Departamentos()
    {
        return $this->hasMany('App\Model\Catalogo\MDepartamento','DEPA_DIRECCION');
    }

    public function DepartamentosExistentes()
    {
        return $this->Departamentos()->siExistente();
    }

    public function DepartamentosDisponibles()
    {
        return $this->Departamentos()->siDisponible();
    }

    public function DepartamentosExistentesDisponibles()
    {
        return $this->Departamentos()->siExistenteDisponible();
    }

    public function Puestos()
    {
        return $this->hasMany('App\Model\Catalogo\MPuesto','PUES_DIRECCION');
    }

    public function Seguimientos()
    {
        return $this->hasMany('App\Model\MSeguimiento','SEGU_DIRECCION');
    }

    /* Presenter */
    public function presenter()
    {
    	return new MDireccionPresenter($this);
    }    

}