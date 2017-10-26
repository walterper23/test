<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModeloRolModuloPermiso extends Model{
    
    protected $table          = 'roles_modulos_permisos';
    protected $primaryKey     = 'RMPE_ROl_MOD_PERMISO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }





    public function rol(){
        return $thi->belongsTo('App\Model\ModeloRol','RMPE_ROL','ROLE_ROL');
    }

    public function modulo(){
        return $thi->belongsTo('App\Model\ModeloModulo','RMPE_MODULO','MODU_MODULO');
    }

    public function permiso(){
        return $this->belongsTo('App\Model\ModeloPermiso','RMPE_PERMISO','PERM_PERMISO');
    }

}
