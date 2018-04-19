<?php
namespace App\Model;

class MDocumentoDenuncia extends BaseModel
{
    protected $table        = 'documentos_denuncias';
    protected $primaryKey   = 'DODE_DOCUMENTO_DENUNCIA';
    protected $prefix       = 'DODE';
    
    /** Relationships **/

    public function Documento()
    {
        return $this -> belongsTo('App\Model\MDocumento','DOCU_DOCUMENTO','DODE_DOCUMENTO');
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

    /* Presenter */

}