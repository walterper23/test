<?php
namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Presenters\MUsuarioPresenter;

class MUsuario extends Authenticatable {

    use BaseModelTrait;
    
    protected $table          = 'usuarios';
    protected $primaryKey     = 'USUA_USUARIO';
    public    $timestamps     = false;

    protected $fieldEnabled   = 'USUA_ENABLED';
    protected $fieldDeleted   = 'USUA_DELETED';

    public function isSuperAdmin(){
        return $this -> getKey() == 1;
    }

    public function getAuthUsername(){
        return $this -> attributes['USUA_USERNAME'];
    }    

    public function setUsuaPasswordAttribute( $password ){
        $this -> attributes['USUA_PASSWORD'] = bcrypt($password);
    }

    public function getAuthPassword(){
        return $this -> attributes['USUA_PASSWORD'];
    }
    
    public function getNombre(){
        return $this -> attributes['USUA_NOMBRE'];
    }

    public function getAvatarSmall(){
        return $this -> attributes['USUA_AVATAR_SMALL'];
    }

    public function getAvatarFull(){
        return $this -> attributes['USUA_AVATAR_FULL'];
    }
    
    public function getRecentLogin(){
        return $this -> attributes['USUA_RECENT_LOGIN'];
    }

    public function getLastLogin(){
        return $this -> attributes['USUA_LAST_LOGIN'];
    }

    public function getRememberTokenName(){
        return $this -> attributes['USUA_REMEMBER_TOKEN'];
    }

    public function setRememberToken($value){
        $this -> attributes['USUA_REMEMBER_TOKEN'] = $value;
    }

    public function canAtLeast(){
        $permisos = func_get_args();
        foreach ($permisos as $permiso) {
            if ($this -> can($permiso)) {
                return true;
            }   
        }
        return false;
    }

    /* Relationships */

    public function UsuarioAsignaciones(){
        return $this -> hasMany('App\Model\MUsuarioAsignacion','USAS_USUARIO',$this -> getKeyName());
    }

    public function Direcciones(){
        return $this -> belongsToMany('App\Model\Catalogo\MDireccion','usuarios_asignaciones','USAS_USUARIO','USAS_DIRECCION');
    }

    public function Departamentos(){
        return $this -> belongsToMany('App\Model\Catalogo\MDepartamento','usuarios_asignaciones','USAS_USUARIO','USAS_DEPARTAMENTO');
    }

    public function Puestos(){
        return $this -> belongsToMany('App\Model\Catalogo\MPuesto','usuarios_asignaciones','USAS_USUARIO','USAS_PUESTO');
    }

    public function UsuarioDetalle(){
        return $this -> hasOne('App\Model\MUsuarioDetalle','USDE_USUARIO',$this -> getKeyName());
    }

    public function Permisos(){
        return $this -> belongsToMany('App\Model\Acl\MPermiso','usuarios_permisos','USPE_USUARIO','USPE_PERMISO');
    }

    /* Presenter */

    public function presenter(){
        return new MUsuarioPresenter($this);
    }
}