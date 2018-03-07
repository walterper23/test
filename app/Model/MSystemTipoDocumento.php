<?php
namespace App\Model\System;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MSystemTipoDocumentoPresenter;

class MSystemTipoDocumento extends BaseModel {
    
    protected $table          = 'system_tipos_documentos';
    protected $primaryKey     = 'SYTD_TIPO_DOCUMENTO';
    public    $timestamps     = false;

    protected $fieldCreatedBy = 'SYTD_CREATED_BY';
    protected $fieldUpdated   = 'SYTD_UPDATED';

    public function getNombre(){
    	return $this -> attributes['SYTD_NOMBRE_TIPO'];
    }

    /* Relationships */

    public function documentos(){
        return $this -> hasMany('App\Model\MDocumento','DOCU_TIPO_DOCUMENTO',$this -> getKey());
    }



    /* Presenter */

    public function presenter(){
        return new MSystemTipoDocumentoPresenter($this);
    }

}