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

    public function getTipo()
    {
        return $this->getAttribute('RECE_TIPO');
    }

    public function scopeIsLocal($query)
    {
        return $query->where('RECE_TIPO',1);
    }

    public function scopeIsForaneo($query)
    {
        return $query->where('RECE_TIPO',2);
    }

    /* Relationships */

    public function Usuario()
    {
        return $this->belongsTo('App\Model\MUsuario','USUA_USUARIO','RECE_USUARIO');
    }

}