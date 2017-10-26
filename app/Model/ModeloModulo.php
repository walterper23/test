<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModeloModulo extends Model{
    
    protected $table          = 'modulos';
    protected $primaryKey     = 'MODU_MODULO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }

    public function getNombre(){
    	return $this->attributes['MODU_NOMBRE'];
    }



    public function permisos(){
    	return $this->hasMany('App\Model\ModeloModuloPermiso', 'MOPE_MODULO', $this->primaryKey);
    }


}
