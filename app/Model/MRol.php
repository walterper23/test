<?php

namespace App\Model\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Acl\Models\Role;

class MRol extends Role{
    
    protected $table          = 'acl_roles';
    protected $primaryKey     = 'ROLE_ROL';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];


    /* Relationships */

    public function usuarios(){
        return $this->belongsToMany(config('auth.providers.users.model'), 'usuarios_acl_roles','USRO_USUARIO','USRO_USUARIO');
    }
    /****************/

    
    /* Overrides methods :: trait HasPermission */

    public function permissions(){
        return $this->belongsToMany(config('acl.permission'),'acl_roles_acl_permisos','ROPE_ROL','ROPE_PERMISO');
    }

    public function getPermissions(){
        return $this->permissions->pluck('ROLE_SLUG')->toArray();
    }

    /******************************************/

}
