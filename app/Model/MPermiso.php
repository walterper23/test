<?php

namespace App\Model\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Acl\Models\Permission;

class MPermiso extends Permission{

    protected $table          = 'acl_permisos';
    protected $primaryKey     = 'acl_roles';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }

    public function getDefaultRoute(){
        return $this->attributes['ROLE_DEFAULT_ROUTE'];
    }



}
