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
    public function documento(){
        return $this->belongsTo('App\Model\MDocumento','SEGU_DOCUMENTO','DOCU_DOCUMENTO');
    }

    public function usuario(){
        return $this->belongsTo('App\Model\MUsuario','SEGU_USUARIO','USUA_USUARIO');
    }

    public function status(){
        return $this->belongsTo('App\Model\MStatus','SEGU_STATUS','STAT_STATUS');
    }

    /******************/

    public function presenter(){
        return new MSeguimientoPresenter($this);
    }

}
