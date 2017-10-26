<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModeloRol extends Model{
    
    protected $table          = 'roles';
    protected $primaryKey     = 'ROLE_ROL';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }

    public function getDefaultRoute(){
        return $this->attributes['ROLE_DEFAULT_ROUTE'];
    }







    public function rolesModulosPermisos(){
        return $this->hasMany('App\Model\ModeloRolModuloPermiso', 'RMPE_ROL', $this->primaryKey);
    }

}
