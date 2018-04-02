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

    /* Methods */

    public function resuelto()
    {
        return $this -> attributes['DOCU_SYSTEM_ESTADO_DOCTO'] == 4; // Documento resuelto
    }


    /* Relationships */

    public function Denuncia()
    {
        return $this -> hasOne('App\Model\MDenuncia','DENU_DOCUMENTO',$this -> getKeyName());
    }

    public function Detalle()
    {
        return $this -> hasOne('App\Model\MDetalle','DETA_DETALLE','DOCU_DETALLE');
    }

    public function EstadoDocumento()
    {
        return $this -> hasOne('App\Model\Sistema\MSistemaEstadoDocumento','SYED_ESTADO_DOCUMENTO','DOCU_SYSTEM_ESTADO_DOCTO');
    }

    public function Marcadores()
    {
        return $this -> hasOne('App\Model\MMarcador','DOMA_DOCUMENTO',$this -> getKeyName());
    }

    public function Seguimientos()
    {
        return $this -> hasMany('App\Model\MSeguimiento','SEGU_DOCUMENTO',$this -> getKeyName());
    }

    public function TipoDocumento()
    {
        return $this -> hasOne('App\Model\Sistema\MSistemaTipoDocumento','SYTD_TIPO_DOCUMENTO','DOCU_SYSTEM_TIPO_DOCTO');
    }

}