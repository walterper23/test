<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MDocumentoDetalle extends Model {
    
    protected $table          = 'documentos_detalles';
    protected $primaryKey     = 'DODE_DOCUMENTO_DETALLE';
    public    $timestamps     = false;


    /** Relationships **/


    public function documento(){
        return $this->belongsTo('App\Model\MDocumento','DOCU_DOCUMENTO','DODE_DOCUMENTO');
    }

    /* Presenter */

}
