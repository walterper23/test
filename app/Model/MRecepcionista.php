<?php
namespace App\Model;

/* Models */
use App\Model\BaseModel;

class MRecepcionista extends BaseModel
{
    protected $table        = 'recepcionistas';
    protected $primaryKey   = 'RECE_RECEPCIONISTA';
    protected $prefix       = 'RECE';

    /* Methods */

    public function getUsuario()
    {
        return $this->getAttribute('RECE_USUARIO');
    }

    public function getFolioEstructura()
    {
        return $this->getAttribute('RECE_FOLIO_ESTRUCTURA');
    }

    /* Relationships */

    public function Usuario()
    {
        return $this->belongsTo('App\Model\MUsuario','USUA_USUARIO','RECE_USUARIO');
    }

}