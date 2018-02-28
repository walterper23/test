<?php
namespace App\Model;

class MUsuarioAsignacion extends BaseModel {
    
    protected $table          = 'usuarios_asignaciones';
    protected $primaryKey     = 'USAS_ASIGNACION';
    public    $timestamps     = false;

    /* Relationships */

    public function Direccion(){
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','USAS_DIRECCION','DIRE_DIRECCION');
    }

    public function Departamento(){
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','USAS_DEPARTAMENTO','PUES_DEPARTAMENTO');
    }

    public function Usuario(){
        return $this -> belongsTo('App\Model\MUsuario','USAS_USUARIO','USUA_USUARIO');
    }

    

}