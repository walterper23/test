<?php

namespace App\Model\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MDepartamentoPresenter;

class MDepartamento extends Model{
    
    protected $table          = 'cat_departamentos';
    protected $primaryKey     = 'DEPA_DEPARTAMENTO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];





    /** Relationships **/

    public function direccion(){
        return $this->belongsTo('App\Model\Catalogo\MDireccion','DEPA_DIRECCION','DIRE_DIRECCION');
    }

    public function seguimientos(){
        return $this->hasMany('App\Model\MSeguimiento','SEGU_DEPARTAMENTO',$this->getKeyName());
    }

    public function documentos(){
        return $this->hasMany('App\Model\MDocumento','DOCU_DOCUMENTO','');
    }

    /** ************ **/


    public function presenter(){
    	return new MDepartamentoPresenter($this);
    }

}
