<?php
namespace App\Model;

class MDocumentoForaneo extends BaseModel
{
    protected $table        = 'documentos_foraneos';
    protected $primaryKey   = 'DOFO_DOCUMENTO';
    protected $prefix       = 'DOFO';

    public function getNumero()
    {
        return $this -> attributes['DOFO_NUMERO_DOCUMENTO'];
    }

    /* Methods */

    public function resuelto()
    {
        return $this -> attributes['DOFO_SYSTEM_ESTADO_DOCTO'] == 4; // Documento resuelto
    }


    /* Relationships */

    public function Denuncia()
    {
        return $this -> hasOne('App\Model\MDenuncia','DENU_DOCUMENTO',$this -> getKeyName());
    }

    public function Detalle()
    {
        return $this -> hasOne('App\Model\MDetalle','DETA_DETALLE','DOFO_DETALLE');
    }

    public function EstadoDocumento()
    {
        return $this -> hasOne('App\Model\Sistema\MSistemaEstadoDocumento','SYED_ESTADO_DOCUMENTO','DOFO_SYSTEM_ESTADO_DOCTO');
    }

    public function TipoDocumento()
    {
        return $this -> hasOne('App\Model\Sistema\MSistemaTipoDocumento','SYTD_TIPO_DOCUMENTO','DOFO_SYSTEM_TIPO_DOCTO');
    }

}