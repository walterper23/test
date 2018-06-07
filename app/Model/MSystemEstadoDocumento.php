<?php
namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;

/* Presenter */
use App\Presenters\MSystemEstadoDocumentoPresenter;

class MSystemEstadoDocumento extends Model
{
    protected $table          = 'system_estados_documentos';
    protected $primaryKey     = 'SYED_ESTADO_DOCUMENTO';
    public    $timestamps     = false;

    public function getNombre(){
    	return $this -> attributes['SYED_NOMBRE'];
    }

    /* Relationships */



    /* Presenter */

    public function presenter(){
        return new MSystemEstadoDocumentoPresenter($this);
    }

}