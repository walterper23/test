<?php
namespace App\Model\Catalogo;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MDepartamentoPresenter;

class MDepartamento extends BaseModel {
    
    protected $table          = 'cat_departamentos';
    protected $primaryKey     = 'DEPA_DEPARTAMENTO';
    public    $timestamps     = false;


    /* Relationships */

    public function Direccion(){
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','DEPA_DIRECCION','DIRE_DIRECCION');
    }

    public function Seguimientos(){
        return $this -> hasMany('App\Model\MSeguimiento','SEGU_DEPARTAMENTO',$this -> getKeyName());
    }

    public function Documentos(){
        return $this -> hasMany('App\Model\MDocumento','DOCU_DOCUMENTO','');
    }

    /* Presenter */
    public function presenter(){
    	return new MDepartamentoPresenter($this);
    }

}
