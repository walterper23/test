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

    public function departamento(){
        return $this->belongsTo('App\Model\Catalogo\MDepartamento','PUES_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    /** ************ **/


    public function presenter(){
    	return new MPuestoPresenter($this);
    }
}
