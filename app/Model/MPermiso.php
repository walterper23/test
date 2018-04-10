<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MPermiso extends Model
{
    protected $table       = 'system_permisos';
    protected $primaryKey  = 'SYPE_PERMISO';
    public    $timestamps  = false;

    public function getCodigo()
    {
    	return $this -> attributes['SYPE_CODIGO'];
    }

    public function getDescripcion()
    {
    	return $this -> attributes['SYPE_DESCRIPCION'];
    }

    /* Relationships */

    public function Recurso()
    {
        return $this -> belongsTo('App\Model\MRecurso','SYPE_RECURSO','SYRE_RECURSO');
    }

}