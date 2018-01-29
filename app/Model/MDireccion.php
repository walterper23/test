<?php

namespace App\Model\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MDireccionPresenter;

class MDireccion extends Model{
    
    protected $table          = 'cat_direcciones';
    protected $primaryKey     = 'DIRE_DIRECCION';
    public    $timestamps     = false;

    protected $fillable = [
    ];

    protected $hidden = [
    ];



    /** Relationships **/

    public function departamentos(){
        return $this->hasMany('App\Model\Catalogo\MDepartamento','DEPA_DIRECCION',$this->primaryKey)->where('DEPA_DELETED',0);
    }

    public function seguimientos(){
        return $this->hasMany('App\Model\MSeguimiento','SEGU_DIRECCION',$this->getKeyName());
    }

    /** ************ **/


    public function presenter(){
    	return new MDireccionPresenter($this);
    }    

}
