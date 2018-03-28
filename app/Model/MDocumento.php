<?php
namespace App\Model;

class MDocumento extends BaseModel
{
    protected $table        = 'documentos';
    protected $primaryKey   = 'DOCU_DOCUMENTO';
    protected $prefix       = 'DOCU';

    public function getNumero()
    {
        return $this -> attributes['DOCU_NUMERO_DOCUMENTO'];
    }


    /* Relationships */

    public function Detalle()
    {
        return $this -> hasOne('App\Model\MDetalle','DETA_DETALLE','DOCU_DETALLE');
    }

    public function Seguimientos()
    {
        return $this -> hasMany('App\Model\MSeguimiento','SEGU_DOCUMENTO',$this -> getKeyName());
    }

}