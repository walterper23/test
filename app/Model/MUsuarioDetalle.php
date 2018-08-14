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
        return $this -> getAttribute('USDE_NO_TRABAJADOR');
    }

    public function getNombres()
    {
        return $this -> getAttribute('USDE_NOMBRES');
    }
    
    public function getApellidos()
    {
        return $this -> getAttribute('USDE_APELLIDOS');
    }

    public function getGenero()
    {
        return $this -> getAttribute('USDE_GENERO');
    }

    public function getEmail()
    {
        return $this -> getAttribute('USDE_EMAIL');
    }

    public function getTelefono()
    {
        return $this -> getAttribute('USDE_TELEFONO');
    }
    
    /* Relationships */

    public function Usuario()
    {
        return $this -> belongsTo('App\Model\MUsuario','USUA_USUARIO','USDE_USUARIO');
    }

    public function Puesto()
    {
        return $this -> hasOne('App\Model\Catalogo\MPuesto','PUES_PUESTO','USDE_PUESTO');
    }


    /* Presenter */
    public function presenter(){
        return new MUsuarioDetallePresenter($this);
    }
}