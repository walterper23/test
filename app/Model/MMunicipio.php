<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MMunicipio extends Model
{
    protected $table      = 'municipios';
    protected $primaryKey = 'MUNI_MUNICIPIO';
    public    $timestamps = false;

    /* Methods */

    public function getClave()
    {
    	return $this->getAttribute('MUNI_CLAVE'); 
    }

    public function getNombre()
    {
    	return $this->getAttribute('MUNI_NOMBRE'); 
    }

    public function scopeDisponible($query)
    {
    	return $query->where('MUNI_ENABLED',1);
    }

}