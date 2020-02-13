<?php
namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;

class MSystemPreferencia extends Model
{
    protected $table      = 'system_preferencias';
    protected $primaryKey = 'SYPR_PREFERENCIA';
    public $timestamps    = false;
    
    /* Methods */
    public function getCodigo()
    {
        return $this->getAttribute('SYPR_CODIGO');
    }

    public function getNombre()
    {
        return $this->getAttribute('SYPR_NOMBRE');
    }

    public function getDescripcion()
    {
        return $this->getAttribute('SYPR_DESCRIPCION');
    }

    public static function setAllPreferencias()
    {
        cache()->forget('System.Preferencias');

        cache()->rememberForever('System.Preferencias',function(){
            return self::where('SYPR_ENABLED',1)->get();
        });
    }

    /* Relationships */
    public function Usuarios()
    {
        return $this->belongsToMany('App\Model\MUsuario','usuarios_preferencias','USPR_PREFERENCIA','USPR_USUARIO');
    }

}