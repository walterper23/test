<?php
namespace App\Model;

/* Presenter */
use App\Presenters\MAnexoPresenter;

class MDetalle extends BaseModel
{
	protected $table        = 'detalles';
	protected $primaryKey   = 'DETA_DETALLE';
    protected $prefix       = 'DETA';	

    public function getFechaRecepcion()
    {
        return $this -> attributes['DETA_FECHA_RECEPCION'];
    }

    public function getDescripcion()
    {
        return $this -> attributes['DETA_DESCRIPCION'];
    }

    public function getAnexos()
    {
        return $this -> attributes['DETA_ANEXOS'];
    }

    public function getResponsable()
    {
        return $this -> attributes['DETA_RESPONSABLE'];
    }

    /* Relationships */

	public function municipio()
    {
        return $this -> hasOne('App\Model\MMunicipio', 'MUNI_MUNICIPIO', 'DETA_MUNICIPIO');
    }


    /* Presenter */    
    public function presenter()
    {
        return new MAnexoPresenter($this);
    }

}