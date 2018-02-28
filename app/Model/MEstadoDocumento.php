<?php
namespace App\Model\Catalogo;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MEstadoDocumentoPresenter;

class MEstadoDocumento extends BaseModel {
    
    protected $table          = 'cat_estados_documentos';
    protected $primaryKey     = 'ESDO_ESTADO_DOCUMENTO';
    public    $timestamps     = false;

    public function getNombre(){
    	return $this -> attributes['ESDO_NOMBRE'];
    }


    /* Relationships */

    public function Documentos(){
        return $this -> hasMany('App\Model\MDocumento','DOCU_STATUS',$this -> getKey());
    }
    
    public function Direccion(){
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','ESDO_DIRECCION','DIRE_DIRECCION');
    }
    
    public function Departamento(){
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','ESDO_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    /* Presenter */

    public function presenter(){
        return new MEstadoDocumentoPresenter($this);
    }

}