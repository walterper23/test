<?php
namespace App\Model\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Acl\Models\Permission;

class MPermiso extends Permission {

    protected $table          = 'acl_permisos';
    protected $primaryKey     = 'PERM_PERMISO';
    public    $timestamps     = false;


    /* Relationships */

    // @override :: trait HasRole
    public function roles(){
        return $this->belongsToMany(config('acl.role'),'acl_roles_acl_permisos','ROPE_PERMISO','ROPE_ROL');
    }

}