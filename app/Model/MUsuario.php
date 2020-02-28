<?php
namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
//use App\Notifications\UserCustomResetPasswordNotification as ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;

/* Presenter */
use App\Presenters\MUsuarioPresenter;

class MUsuario extends Authenticatable
{
    use BaseModelTrait, Notifiable;
    
    protected $table        = 'usuarios';
    protected $primaryKey   = 'USUA_USUARIO';
    protected $prefix       = 'USUA';
    public    $timestamps   = false;

    // protected $hidden = ['USUA_PASSWORD'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model){
            $model->creatingRegister();
        });
    }

    /* Methods */

    public function isSuperAdmin()
    {
        return $this->getKey() == 1;
    }

    public function getEmailForPasswordReset()
    {
        return $this->UsuarioDetalle->getEmail();
    }
    
    public function getAuthUsername()
    {
        return $this->getAttribute('USUA_USERNAME');
    }

    public function getAuthPassword()
    {
        return $this->getAttribute('USUA_PASSWORD');
    }

    public function getDescripcion()
    {
        return $this->getAttribute('USUA_DESCRIPCION');
    }

    public function getAvatarSmall()
    {
        return $this->getAttribute('USUA_AVATAR_SMALL');
    }

    public function getAvatarFull()
    {
        return $this->getAttribute('USUA_AVATAR_FULL');
    }

    public function getAvatarDefault( $genero = 'HOMBRE' )
    {
        if ($genero == 'HOMBRE') {
            return 'no-profile-male.png';
        }

        return 'no-profile-female.png';
    }
    
    public function getRecentLogin()
    {
        return $this->getAttribute('USUA_RECENT_LOGIN');
    }

    public function getLastLogin()
    {
        return $this->getAttribute('USUA_LAST_LOGIN');
    }

    public function getRememberTokenName()
    {
        return $this->getAttribute('USUA_REMEMBER_TOKEN');
    }

    public function setRememberToken($value){
        $this->setAttribute('USUA_REMEMBER_TOKEN', $value);
    }

    public function canAtLeast()
    {
        $permisos = func_get_args();
        foreach ($permisos as $permiso)
        {
            if ($this->can($permiso)){
                return true;
            }
        }
        
        return false;
    }

    /* Relationships */

    public function UsuarioAsignaciones()
    {
        return $this->hasMany('App\Model\MUsuarioAsignacion','USAS_USUARIO',$this->getKeyName());
    }

    public function Direcciones()
    {
        return $this->belongsToMany('App\Model\Catalogo\MDireccion','usuarios_asignaciones','USAS_USUARIO','USAS_DIRECCION')->whereNull('USAS_DEPARTAMENTO');
    }

    public function Departamentos()
    {
        return $this->belongsToMany('App\Model\Catalogo\MDepartamento','usuarios_asignaciones','USAS_USUARIO','USAS_DEPARTAMENTO');
    }

    public function Puestos()
    {
        return $this->belongsToMany('App\Model\Catalogo\MPuesto','usuarios_asignaciones','USAS_USUARIO','USAS_PUESTO');
    }

    public function UsuarioDetalle()
    {
        return $this->belongsTo('App\Model\MUsuarioDetalle','USUA_DETALLE');
    }

    public function Preferencias()
    {
        return $this->belongsToMany('App\Model\System\MSystemPreferencia','usuarios_preferencias','USPR_USUARIO','USPR_PREFERENCIA');
    }

    public function Recepcionista()
    {
        return $this->hasOne('App\Model\MRecepcionista','RECE_USUARIO',$this->getKeyName());
    }


    /* Notifications */

    /*public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }*

    /* Presenter */

    public function presenter()
    {
        return new MUsuarioPresenter($this);
    }
}