<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModeloPermiso extends Model{
    
    protected $table          = 'permisos';
    protected $primaryKey     = 'PERM_PERMISO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }

    public function getNombre(){
    	return $this->attributes['PERM_NOMBRE'];
    }



    public function modulos(){
    	return $this->hasMany('App\Model\ModeloModuloPermiso', 'MOPE_PERMISO', $this->primaryKey);
    }

}
