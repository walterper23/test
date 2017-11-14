<?php

namespace App\Model\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MPuestoPresenter;

class MPuesto extends Model{
    
    protected $table          = 'cat_puestos';
    protected $primaryKey     = 'PUES_PUESTO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    




    /** Relationships **/



    /** ************ **/


    public function presenter(){
    	return new MPuestoPresenter($this);
    }
}
