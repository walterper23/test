<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MMunicipio extends Model {
    
    protected $table          = 'municipios';
    protected $primaryKey     = 'MUNI_MUNICIPIO';
    public    $timestamps     = false;

    public function getCodigo(){
        return str_pad(self::getID(), 3, '0', STR_PAD_LEFT);
    }

    public function getNombre(){
    	return $this->attributes[ 'MUNI_NOMBRE' ]; 
    }

}
