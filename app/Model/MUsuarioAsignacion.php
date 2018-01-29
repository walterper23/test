<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MUsuarioAsignacion extends BaseModel {
    
    protected $table          = 'usuarios_asignaciones';
    protected $primaryKey     = 'USAS_ASIGNACION';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];


    /* Relationships */

    public function direccion(){
        return $this->hasOne('App\Model\Catalogo\MDireccion','DIRE_DIRECCION','USAS_DIRECCION');
    }

    public function departamento(){
        return $this->hasOne('App\Model\Catalogo\MDepartamento','USAS_DEPARTAMENTO','PUES_DEPARTAMENTO');
    }

    /****************/

    

}
