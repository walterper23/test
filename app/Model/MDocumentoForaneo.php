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

    public function getTipoDocumento()
    {
        return $this -> attributes['DOFO_SYSTEM_TIPO_DOCTO'];
    }

    public function getTipoRecepcion()
    {
        return 2;
    }

    public function isLocal()
    {
        return $this -> getTipoRecepcion() == 1;
    }

    public function isForaneo()
    {
        return $this -> getTipoRecepcion() == 2;
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

    public function AcuseRecepcion()
    {
        return $this -> hasOne('App\Model\MAcuseRecepcion','ACUS_DOCUMENTO',$this -> getKeyName()) -> where('ACUS_CAPTURA',2);
    }

    public function Denuncia()
    {
        return $this -> hasOne('App\Model\MDenuncia','DENU_DOCUMENTO',$this -> getKeyName());
    }

    public function Detalle()
    {
        return $this -> hasOne('App\Model\MDetalle','DETA_DETALLE','DOFO_DETALLE');
    }

    public function DocumentoDenuncia()
    {
        return $this -> hasOne('App\Model\MDocumentoDenuncia','DODE_DOCUMENTO_FORANEO',$this -> getKeyName());
    }

    public function Escaneos()
    {
        return $this -> hasMany('App\Model\MEscaneo','ESCA_DOCUMENTO_FORANEO',$this -> getKeyName());
    }

    public function TipoDocumento()
    {
        return $this -> hasOne('App\Model\System\MSystemTipoDocumento','SYTD_TIPO_DOCUMENTO','DOFO_SYSTEM_TIPO_DOCTO');
    }

}