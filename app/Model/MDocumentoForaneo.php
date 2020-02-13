<?php
namespace App\Model;

/* Presenter */
use App\Presenters\MDocumentoForaneoPresenter;

class MDocumentoForaneo extends BaseModel
{
    protected $table        = 'documentos_foraneos';
    protected $primaryKey   = 'DOFO_DOCUMENTO';
    protected $prefix       = 'DOFO';

    /* Methods */

    public function getDetalle()
    {
        return $this->getAttribute('DOFO_DETALLE');
    }

    public function getNumero()
    {
        return $this->getAttribute('DOFO_NUMERO_DOCUMENTO');
    }

    public function getTipoDocumento()
    {
        return $this->getAttribute('DOFO_SYSTEM_TIPO_DOCTO');
    }

    public function enviado()
    {
        return $this->getAttribute('DOFO_ENVIADO') == 1;
    }

    public function noEnviado()
    {
        return !$this->enviado();
    }

    public function getFechaEnviado()
    {
        return $this->getAttribute('DOFO_FECHA_ENVIADO');
    }

    public function recibido()
    {
        return $this->getAttribute('DOFO_RECIBIDO') == 1;
    }

    public function getFechaRecibido()
    {
        return $this->getAttribute('DOFO_FECHA_RECIBIDO');
    }

    public function validado()
    {
        return $this->getAttribute('DOFO_VALIDADO') == 1;
    }

    public function getFechaValidado()
    {
        return $this->getAttribute('DOFO_FECHA_VALIDADO');
    }

    public function recepcionado()
    {
        return $this->getAttribute('DOFO_RECEPCIONADO') == 1;
    }

    public function getFechaRecepcionado()
    {
        return $this->getAttribute('DOFO_FECHA_RECEPCIONADO');
    }

    /* Local Scopes */

    public function scopeGuardado($query)
    {
        return $query->where('DOFO_GUARDADO',1);
    }

    public function scopeNoGuardado($query)
    {
        return $query->where('DOFO_GUARDADO',0);
    }

    /* Relationships */

    public function AcuseRecepcion()
    {
        return $this->hasOne('App\Model\MAcuseRecepcion','ACUS_DOCUMENTO',$this->getKeyName())->where('ACUS_CAPTURA',2);
    }

    public function Denuncia()
    {
        return $this->hasOne('App\Model\MDenuncia','DENU_DOCUMENTO',$this->getKeyName());
    }

    public function Detalle()
    {
        return $this->hasOne('App\Model\MDetalle','DETA_DETALLE','DOFO_DETALLE');
    }

    public function DocumentoDenuncia()
    {
        return $this->hasOne('App\Model\MDocumentoDenuncia','DODE_DOCUMENTO_FORANEO',$this->getKeyName());
    }

    public function Documento()
    {
        return $this->hasOne('App\Model\MDocumento','DOCU_DOCUMENTO','DOFO_DOCUMENTO_LOCAL');
    }

    public function Escaneos()
    {
        return $this->hasMany('App\Model\MEscaneo','ESCA_DOCUMENTO_FORANEO',$this->getKeyName());
    }

    public function TipoDocumento()
    {
        return $this->hasOne('App\Model\System\MSystemTipoDocumento','SYTD_TIPO_DOCUMENTO','DOFO_SYSTEM_TIPO_DOCTO');
    }

    /* Presenter */
    public function presenter()
    {
        return new MDocumentoForaneoPresenter($this);
    }

}