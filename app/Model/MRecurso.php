<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MRecurso extends Model
{
    protected $table       = 'system_recursos';
    protected $primaryKey  = 'SYRE_RECURSO';
    public    $timestamps  = false;

    public function getNombre()
    {
    	return $this -> attributes['SYRE_NOMBRE'];
    }

    public function Permisos()
    {
    	return $this -> hasMany('App\Model\MPermiso','SYPE_RECURSO',$this -> getKeyName());
    }

}