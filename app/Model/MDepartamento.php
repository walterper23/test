<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MDepartamentoPresenter;

class MDepartamento extends Model{
    
    protected $table          = 'departamentos';
    protected $primaryKey     = 'DEPA_DEPARTAMENTO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];





    /** Relationships **/


    /** ************ **/


    public function presenter(){
    	return new MDepartamentoPresenter($this);
    }

}
