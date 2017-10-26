<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MDireccion extends Model{
    
    protected $table          = 'direcciones';
    protected $primaryKey     = 'DIRE_DIRECCION';
    public    $timestamps     = false;

    protected $fillable = [
    ];

    protected $hidden = [
    ];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }

    

}
