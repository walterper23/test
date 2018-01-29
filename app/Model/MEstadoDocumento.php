<?php

namespace App\Model\Catalogo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MEstadoDocumentoPresenter;

class MEstadoDocumento extends Model{
    
    protected $table          = 'cat_estados_documentos';
    protected $primaryKey     = 'ESDO_ESTADO_DOCUMENTO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    public function getNombre(){
    	return $this->attributes['ESDO_NOMBRE'];
    }



    /** Relationships **/

    public function documentos(){
        return $this->hasMany('App\Model\MDocumento','DOCU_STATUS',$this->primaryKey);
    }
    
    public function direccion(){
        return $this->belongsTo('App\Model\Catalogo\MDireccion','ESDO_DIRECCION','DIRE_DIRECCION');
    }
    
    public function departamento(){
        return $this->belongsTo('App\Model\Catalogo\MDepartamento','ESDO_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    /** ************ **/

    public function presenter(){
        return new MEstadoDocumentoPresenter($this);
    }

}
