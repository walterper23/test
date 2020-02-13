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

    /* Methods */
    public function getNombre(){
    	return $this->getAttribute('SYED_NOMBRE');
    }

    public static function getAllEstadosDocumentos()
    {
        return cache()->rememberForever('System.Estados.Documentos',function(){
            return self::all();
        });

    }

    /* Relationships */



    /* Presenter */

    public function presenter(){
        return new MSystemEstadoDocumentoPresenter($this);
    }

}