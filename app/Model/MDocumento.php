<?php
namespace App\Model;

class MDocumento extends BaseModel {
    
    protected $table        = 'documentos';
    protected $primaryKey   = 'DOCU_DOCUMENTO';
    

    public function getNumero(){
        return $this -> attributes['DOCU_NUMERO_DOCUMENTO'];
    }


    public function documentoDetalle(){
        return $this -> haOne('App\Model\MDocumentoDetalle','DODE_DOCUMENTO',$this -> getKey());
    }

    public function seguimientos(){
        return $this -> hasMany('App\Model\MSeguimiento','SEGU_DOCUMENTO',$this -> getKeyName());
    }

}