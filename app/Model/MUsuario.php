<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class MUsuario extends Authenticatable{
    
    protected $table          = 'usuarios';
    protected $primaryKey     = 'USUA_USUARIO';
    public    $timestamps     = false;

    protected $fillable = [
        'USUA_USERNAME', 'USUA_EMAIL'
    ];

    protected $hidden = [
        'USUA_PASSWORD', 'USUA_REMEMBER_TOKEN',
    ];

    public function getAuthPassword(){
    	return $this->attributes['USUA_PASSWORD'];
    }

    public function getName(){
    	return $this->attributes['USUA_NOMBRE'];
    }

    public function getRecentLogin(){
        return $this->attributes['USUA_RECENT_LOGIN'];
    }

    public function getLastLogin(){
        return $this->attributes['USUA_LAST_LOGIN'];
    }

    /***** Relationships *****/

    public function rol(){
    	return $this->hasOne('App\Model\ModeloRol', 'ROLE_ROL', 'USUA_ROL');
    }
}
