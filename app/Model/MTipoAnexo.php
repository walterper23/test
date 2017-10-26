<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MTipoAnexo extends Model{
    
    protected $table          = 'cat_tipos_anexos';
    protected $primaryKey     = 'TIAN_TIPO_ANEXO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getID(){
    	return $this->attributes[ $this->primaryKey ];
    }

}
