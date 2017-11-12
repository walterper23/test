<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MStatusPresenter;

class MStatus extends Model{
    
    protected $table          = 'status';
    protected $primaryKey     = 'STAT_STATUS';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getNombre(){
    	return $this->attributes['STAT_NOMBRE'];
    }






    /** Relationships **/

    public function documentos(){
        return $this->hasMany('App\Model\MDocumento','DOCU_STATUS',$this->primaryKey);
    }

    /** ************ **/

    public function presenter(){
        return new MStatusPresenter($this);
    }

}
