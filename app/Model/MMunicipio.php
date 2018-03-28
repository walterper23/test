<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MMunicipio extends Model
{
    protected $table          = 'municipios';
    protected $primaryKey     = 'MUNI_MUNICIPIO';
    protected $prefix         = 'MUNI';
    public    $timestamps     = false;

    public function getNombre()
    {
    	return $this -> attributes['MUNI_NOMBRE']; 
    }

}