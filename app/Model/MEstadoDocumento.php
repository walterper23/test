<?php
namespace App\Model\Catalogo;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MEstadoDocumentoPresenter;

class MEstadoDocumento extends BaseModel
{
    protected $table        = 'cat_estados_documentos';
    protected $primaryKey   = 'ESDO_ESTADO_DOCUMENTO';
    protected $prefix       = 'ESDO';

    public function getNombre()
    {
    	return $this -> getAttribute('ESDO_NOMBRE');
    }

    public function getDireccion()
    {
        return $this -> getAttribute('ESDO_DIRECCION');
    }

    public function getDepartamento()
    {
        return $this -> getAttribute('ESDO_DEPARTAMENTO');
    }

    /* Relationships */

    public function Documentos()
    {
        return $this -> hasMany('App\Model\MDocumento','DOCU_STATUS',$this -> getKey());
    }
    
    public function Direccion()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','ESDO_DIRECCION','DIRE_DIRECCION');
    }
    
    public function Departamento()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','ESDO_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    /* Presenter */

    public function presenter()
    {
        return new MEstadoDocumentoPresenter($this);
    }

}