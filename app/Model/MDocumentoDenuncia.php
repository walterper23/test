<?php
namespace App\Model;

class MDocumentoDenuncia extends BaseModel
{
    protected $table        = 'documentos_denuncias';
    protected $primaryKey   = 'DODE_DOCUMENTO_DENUNCIA';
    protected $prefix       = 'DODE';
    
    /** Relationships **/

    public function DocumentoLocal()
    {
        return $this -> belongsTo('App\Model\MDocumento','DOCU_DOCUMENTO','DODE_DOCUMENTO_LOCAL');
    }

    public function DocumentoForaneo()
    {
        return $this -> belongsTo('App\Model\MDocumento','DOCU_DOCUMENTO','DODE_DOCUMENTO_FORANEO');
    }

    public function DocumentoOrigen()
    {
        return $this -> belongsTo('App\Model\MDocumento','DOCU_DOCUMENTO','DODE_DOCUMENTO_ORIGEN');
    }

    public function Denuncia()
    {
    	return $this -> belongsTo('App\Model\MDocumento','DOCU_DOCUMENTO','DODE_DENUNCIA');
    }

    public function Detalle()
    {
    	return $this -> hasOne('App\Model\MDetalle','DETA_DETALLE','DODE_DETALLE');
    }

    public function Seguimiento()
    {
        return $this -> hasOne('App\Model\MSeguimiento','SEGU_SEGUIMIENTO','DODE_SEGUIMIENTO');
    }

    /* Presenter */

}