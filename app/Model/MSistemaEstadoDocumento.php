<?php
namespace App\Model\Sistema;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MSistemaEstadoDocumentoPresenter;

class MSistemaEstadoDocumento extends BaseModel {
    
    protected $table          = 'system_estados_documentos';
    protected $primaryKey     = 'SYED_ESTADO_DOCUMENTO';
    public    $timestamps     = false;

    protected $fieldEnabled   = 'SYED_ENABLED';
    protected $fieldUpdated   = 'SYED_UPDATED';

    public function getNombre(){
    	return $this -> attributes['SYED_NOMBRE'];
    }

    /* Relationships */



    /* Presenter */

    public function presenter(){
        return new MSistemaEstadoDocumentoPresenter($this);
    }

}