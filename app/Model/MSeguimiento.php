<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MSeguimientoPresenter;

class MSeguimiento extends Model{
    
    protected $table          = 'seguimiento';
    protected $primaryKey     = 'SEGU_SEGUIMIENTO';
    public    $timestamps     = false;

    protected $fillable = [];

    protected $hidden = [];

    




    /** Relationships **/

    public function direccion(){
        return $this->belongsTo('App\Model\Catalogo\MDireccion','SEGU_DIRECCION','DIRE_DIRECCION');
    }

    public function departamento(){
        return $this->belongsTo('App\Model\Catalogo\MDepartamento','SEGU_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    public function documento(){
        return $this->belongsTo('App\Model\MDocumento','SEGU_DOCUMENTO','DOCU_DOCUMENTO');
    }

    public function usuario(){
        return $this->belongsTo('App\Model\MUsuario','SEGU_USUARIO','USUA_USUARIO');
    }

    public function estadoDocumento(){
        return $this->belongsTo('App\Model\Catalogo\MEstadoDocumento','SEGU_ESTADO_DOCUMENTO','ESDO_ESTADO_DOCUMENTO');
    }

    /******************/

    public function presenter(){
        return new MSeguimientoPresenter($this);
    }

}
