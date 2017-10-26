<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModeloStatus extends Model{
    
    protected $table          = 'status';
    protected $primaryKey     = 'STAT_STATUS';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }

    public function getNombre(){
    	return $this->attributes['STAT_NOMBRE'];
    }

}
