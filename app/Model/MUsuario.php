<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Yajra\Acl\Traits\HasRole;

use App\Presenters\MUsuarioPresenter;


class MUsuario extends Authenticatable{

    use HasRole;
    
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

    public function getAvatarSmall(){
        return $this->attributes['USUA_AVATAR_SMALL'];
    }

    public function getAvatarFull(){
        return $this->attributes['USUA_AVATAR_FULL'];
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


    
    /* Relationships */

    public function usuarioAsignaciones(){
        return $this->hasMany('App\Model\MUsuarioAsignacion','USAS_USUARIO',$this->getKeyName());
    }

    public function direcciones(){
        return $this->belongsToMany('App\Model\Catalogo\MDireccion','usuarios_asignaciones','USAS_USUARIO','USAS_DIRECCION');
    }

    public function departamentos(){
        return $this->belongsToMany('App\Model\Catalogo\MDepartamento','usuarios_asignaciones','USAS_USUARIO','USAS_DEPARTAMENTO');
    }

    public function puestos(){
        return $this->belongsToMany('App\Model\Catalogo\MPuesto','usuarios_asignaciones','USAS_USUARIO','USAS_PUESTO');
    }

    public function rol(){
    	return $this->hasOne('App\Model\Acl\MRol','ROLE_ROL','USUA_ROL');
    }

    public function usuarioDetalle(){
        return $this->hasOne('App\Model\MUsuarioDetalle','USDE_USUARIO',$this->getKeyName());
    }

    /***************/

    public function presenter(){
        return new MUsuarioPresenter($this);
    }
}
