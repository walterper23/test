<?php
namespace App\Model;

use App\Presenters\MUsuarioDetallePresenter;

class MUsuarioDetalle extends BaseModel
{
    protected $table        = 'usuarios_detalles';
    protected $primaryKey   = 'USDE_USUARIO_DETALLE';
    protected $prefix       = 'USDE';

    public function getNoTrabajador()
    {
        return $this -> attributes['USDE_NO_TRABAJADOR'];
    }

    public function getNombres()
    {
        return $this -> attributes['USDE_NOMBRES'];
    }
    
    public function getApellidos()
    {
        return $this -> attributes['USDE_APELLIDOS'];
    }

    public function getGenero()
    {
        return $this -> attributes['USDE_GENERO'];
    }

    public function getEmail()
    {
        return $this -> attributes['USDE_EMAIL'];
    }

    public function getTelefono()
    {
        return $this -> attributes['USDE_TELEFONO'];
    }
    
    /* Relationships */

    public function Usuario(){
        return $this -> belongsTo('App\Model\MUsuario','USUA_USUARIO','USDE_USUARIO');
    }


    /* Presenter */
    public function presenter(){
        return new MUsuarioDetallePresenter($this);
    }
}