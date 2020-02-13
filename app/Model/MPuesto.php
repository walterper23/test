<?php
namespace App\Model\Catalogo;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MPuestoPresenter;

class MPuesto extends BaseModel
{    
    protected $table       = 'cat_puestos';
    protected $primaryKey  = 'PUES_PUESTO';
    protected $prefix      = 'PUES';

    /* Methods */
    public function getNombre()
    {
        return $this -> getAttribute('PUES_NOMBRE');
    }

    /** Relationships **/

    public function Direccion()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','PUES_DIRECCION','DIRE_DIRECCION');
    }

    public function Departamento()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','PUES_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    /* Presenter */

    public function presenter()
    {
    	return new MPuestoPresenter($this);
    }
}