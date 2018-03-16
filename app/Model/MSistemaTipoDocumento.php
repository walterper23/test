<?php
namespace App\Model\Sistema;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MSistemaTipoDocumentoPresenter;

class MSistemaTipoDocumento extends BaseModel {
    
    protected $table          = 'system_tipos_documentos';
    protected $primaryKey     = 'SYTD_TIPO_DOCUMENTO';
    public    $timestamps     = false;

    protected $fieldEnabled   = 'SYTD_ENABLED';
    protected $fieldUpdated   = 'SYTD_UPDATED';

    public function getNombre(){
    	return $this -> attributes['SYTD_NOMBRE_TIPO'];
    }

    public function getEtiqueta(){
        return $this -> attributes['SYTD_ETIQUETA_NUMERO'];
    }

    /* Relationships */

    public function documentos(){
        return $this -> hasMany('App\Model\MDocumento','DOCU_TIPO_DOCUMENTO',$this -> getKey());
    }



    /* Presenter */

    public function presenter(){
        return new MSistemaTipoDocumentoPresenter($this);
    }

}