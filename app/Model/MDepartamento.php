<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MDepartamento extends Model{
    
    protected $table          = 'departamentos';
    protected $primaryKey     = 'DEPA_DEPARTAMENTO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }

}
