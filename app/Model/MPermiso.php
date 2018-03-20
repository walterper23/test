<?php
namespace App\Model\Acl;

use Illuminate\Database\Eloquent\Model;

class MPermiso extends Model {

    protected $table          = 'system_permisos';
    protected $primaryKey     = 'SYPE_PERMISO';

    public function getCodigo(){
    	return $this -> attributes['SYPE_CODIGO'];
    }


    /* Relationships */

}