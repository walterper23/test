<?php
namespace App\Model\System;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MSystemTipoDocumentoPresenter;

class MSystemTipoDocumento extends BaseModel
{
    protected $table       = 'system_tipos_documentos';
    protected $primaryKey  = 'SYTD_TIPO_DOCUMENTO';
    protected $prefix      = 'SYTD';

    public function getNombre()
    {
    	return $this -> attributes['SYTD_NOMBRE'];
    }

    public function getEtiqueta()
    {
        return $this -> attributes['SYTD_ETIQUETA_NUMERO'];
    }

    public function getRibbonColor()
    {
        return $this -> attributes['SYTD_RIBBON_COLOR'];
    }


    /* Relationships */

    public function Documentos()
    {
        return $this -> hasMany('App\Model\MDocumento','DOCU_TIPO_DOCUMENTO',$this -> getKeyName());
    }



    /* Presenter */

    public function presenter()
    {
        return new MSystemTipoDocumentoPresenter($this);
    }

}