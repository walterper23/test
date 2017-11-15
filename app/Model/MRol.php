<?php

namespace App\Model\Acl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Acl\Models\Role;

class MRol extends Role{
    
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



}
