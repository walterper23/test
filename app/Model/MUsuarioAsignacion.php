<?php
namespace App\Model;

class MUsuarioAsignacion extends BaseModel
{
    protected $table       = 'usuarios_asignaciones';
    protected $primaryKey  = 'USAS_ASIGNACION';
    protected $prefix      = 'USAS';

    /* Relationships */

    public function Usuario()
    {
        return $this -> belongsTo('App\Model\MUsuario','USAS_USUARIO','USUA_USUARIO');
    }

    public function Direccion()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','USAS_DIRECCION','DIRE_DIRECCION');
    }

    public function Departamento()
    {
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','USAS_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    public function Puesto()
    {
        return $this -> belongsTo('App\Model\Catalogo\MPuesto','USAS_PUESTO','PUES_PUESTO');
    }

}