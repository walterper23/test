<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MMunicipio extends Model {
    
    protected $table          = 'municipios';
    protected $primaryKey     = 'MUNI_MUNICIPIO';
    public    $timestamps     = false;


    public function getNombre(){
    	return $this->attributes[ 'MUNI_NOMBRE' ]; 
    }

}