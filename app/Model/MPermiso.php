<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MPermiso extends Model
{
    protected $table       = 'system_permisos';
    protected $primaryKey  = 'SYPE_PERMISO';
    public    $timestamps  = false;

    /* Methods */
    
    public function getCodigo()
    {
    	return $this->getAttribute('SYPE_CODIGO');
    }

    public function getDescripcion()
    {
    	return $this->getAttribute('SYPE_DESCRIPCION');
    }

    /* Relationships */

    public function Recurso()
    {
        return $this->belongsTo('App\Model\MRecurso','SYPE_RECURSO','SYRE_RECURSO');
    }

}