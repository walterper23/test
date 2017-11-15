<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Presenters\MUsuarioDetallePresenter;

class MUsuarioDetalle extends Authenticatable{
    
    protected $table          = 'usuarios_detalles';
    protected $primaryKey     = 'USDE_USUARIO_DETALLE';
    public    $timestamps     = false;

    protected $fillable = [

    ];

    protected $hidden = [

    ];
    
    public function getNombres(){
        return $this->attributes['USDE_NOMBRES'];
    }
    
    public function getApellidos(){
        return $this->attributes['USDE_APELLIDOS'];
    }
    
    /** Relationships **/

    public function usuario(){
        return $this->belongsTo('App\Model\MUsuario','USUA_USUARIO','USDE_USUARIO');
    }


    /** ************ **/



    public function presenter(){
        return new MUsuarioDetallePresenter($this);
    }
}
