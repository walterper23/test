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

    /* Methods */
    public function getNombre()
    {
    	return $this->getAttribute('SYTD_NOMBRE');
    }

    public function getEtiqueta()
    {
        return $this->getAttribute('SYTD_ETIQUETA_NUMERO');
    }

    public function getCodigoAcuse()
    {
        return $this->getAttribute('SYTD_CODIGO_ACUSE');
    }

    public function getRibbonColor()
    {
        return $this->getAttribute('SYTD_RIBBON_COLOR');
    }

    public static function getListaRibbonColor()
    {
        return [
            'primary'   => 'Azul',
            'info'      => 'Azul celeste',
            'success'   => 'Verde',
            'danger'    => 'Rojo',
            'warning'   => 'Amarillo',
            'secondary' => 'Gris',
            'dark'      => 'Negro',
            'purple'    => 'Morado',
            'brown'     => 'Café',
        ];
    }

    public static function getAllTiposDocumentos()
    {
        $tipos = cache('System.Tipos.Documentos');

        if( is_null($tipos) )
        {
            self::setAllTiposDocumentos();
            return self::getAllTiposDocumentos();
        }

        return $tipos;
    }

    public static function setAllTiposDocumentos()
    {
        cache()->forget('System.Tipos.Documentos');

        cache()->rememberForever('System.Tipos.Documentos',function(){
            return self::existente()->disponible()->get();
        });
    }

    /* Relationships */

    public function Documentos()
    {
        return $this->hasMany('App\Model\MDocumento','DOCU_TIPO_DOCUMENTO',$this->getKeyName());
    }


    /* Presenter */

    public function presenter()
    {
        return new MSystemTipoDocumentoPresenter($this);
    }

}