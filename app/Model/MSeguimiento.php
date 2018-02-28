<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Presenters\MSeguimientoPresenter;

class MSeguimiento extends BaseModel {
    
    protected $table          = 'seguimiento';
    protected $primaryKey     = 'SEGU_SEGUIMIENTO';
    public    $timestamps     = false;


    /* Relationships */

    public function Direccion(){
        return $this -> belongsTo('App\Model\Catalogo\MDireccion','SEGU_DIRECCION','DIRE_DIRECCION');
    }

    public function Departamento(){
        return $this -> belongsTo('App\Model\Catalogo\MDepartamento','SEGU_DEPARTAMENTO','DEPA_DEPARTAMENTO');
    }

    public function Documento(){
        return $this -> belongsTo('App\Model\MDocumento','SEGU_DOCUMENTO','DOCU_DOCUMENTO');
    }

    public function Usuario(){
        return $this -> belongsTo('App\Model\MUsuario','SEGU_USUARIO','USUA_USUARIO');
    }

    public function EstadoDocumento(){
        return $this -> belongsTo('App\Model\Catalogo\MEstadoDocumento','SEGU_ESTADO_DOCUMENTO','ESDO_ESTADO_DOCUMENTO');
    }

    /* Presenter */

    public function presenter(){
        return new MSeguimientoPresenter($this);
    }

}
