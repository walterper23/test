<?php
namespace App\Model;

class MUsuarioPermiso extends BaseModel
{
    protected $table       = 'usuarios_permisos';
    protected $primaryKey  = 'USPE_USUARIO_PERMISO';
    protected $prefix      = 'USPE';


    /* Relationships */

    public function Usuario()
    {
    	return $this -> belongsTo('App\Model\MUsuario','USPE_USUARIO','USUA_USUARIO');
    }

    public function Permiso()
    {
    	return $this -> belongsTo('App\Model\MPermiso','USPE_PERMISO','USUA_PERMISO');
    }

}