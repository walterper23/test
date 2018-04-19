<?php
namespace App\Model;

class MDocumentoForaneo extends BaseModel
{
    protected $table        = 'documentos_foraneos';
    protected $primaryKey   = 'DOFO_DOCUMENTO';
    protected $prefix       = 'DOFO';

    /* Methods */

    public function getNumero()
    {
        return $this -> attributes['DOFO_NUMERO_DOCUMENTO'];
    }

    public function enviado()
    {
        return $this -> attributes['DOFO_SYSTEM_TRANSITO'] == 1;
    }

    public function recibido()
    {
        return $this -> attributes['DOFO_SYSTEM_TRANSITO'] == 2;
    }

    public function validado()
    {
        return $this -> attributes['DOFO_VALIDADO'] == 1;
    }

    public function recepcionado()
    {
        return $this -> attributes['DOFO_RECEPCIONADO'] == 1;
    }

    /* Local Scopes */

    public function scopeGuardado($query)
    {
        return $query -> where('DOFO_GUARDADO',1);
    }

    public function scopeNoGuardado($query)
    {
        return $query -> where('DOFO_GUARDADO',0);
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

    public function TipoDocumento()
    {
        return $this -> hasOne('App\Model\Sistema\MSistemaTipoDocumento','SYTD_TIPO_DOCUMENTO','DOFO_SYSTEM_TIPO_DOCTO');
    }

}