<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Presenters\MUsuarioPresenter;

class MUsuario extends Authenticatable{
    
    protected $table          = 'usuarios';
    protected $primaryKey     = 'USUA_USUARIO';
    public    $timestamps     = false;

    protected $fillable = [
        'USUA_NOMBRE', 'USUA_EMAIL'
    ];

    protected $hidden = [
        'USUA_PASSWORD', 'USUA_REMEMBER_TOKEN',
    ];

    public function getAuthUsername(){
        return $this->attributes['USUA_USERNAME'];
    }    

    public function getAuthPassword(){
        return $this->attributes['USUA_PASSWORD'];
    }
    
    public function getNombre(){
        return $this->attributes['USUA_NOMBRE'];
    }
    
    public function getRecentLogin(){
        return $this->attributes['USUA_RECENT_LOGIN'];
    }

    public function getLastLogin(){
        return $this->attributes['USUA_LAST_LOGIN'];
    }

    public function getRememberTokenName(){
        return $this->attributes['USUA_REMEMBER_TOKEN'];
    }

    public function setRememberToken($value){
        $this->USUA_REMEMBER_TOKEN = $value;
    }


    
    /** Relationships **/

    public function rol(){
    	return $this->hasOne('App\Model\ModeloRol', 'ROLE_ROL', 'USUA_ROL');
    }



    /** ************ **/



    public function presenter(){
        return new MUsuarioPresenter($this);
    }
}
