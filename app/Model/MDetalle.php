<?php
namespace App\Model;

/* Presenter */
use App\Presenters\MDetallePresenter;

class MDetalle extends BaseModel
{
	protected $table        = 'detalles';
	protected $primaryKey   = 'DETA_DETALLE';
    protected $prefix       = 'DETA';

    /* Methods */

    public function getAnexos()
    {
        return $this -> getAttribute('DETA_ANEXOS');
    }
    
    public function getDescripcion()
    {
        return $this -> getAttribute('DETA_DESCRIPCION');
    }
    
    public function getFechaRecepcion()
    {
        return $this -> getAttribute('DETA_FECHA_RECEPCION');
    }

    public function getMunicipio()
    {
        return $this->getAttribute('DETA_MUNICIPIO');
    }

    public function getObservaciones()
    {
        return $this -> getAttribute('DETA_OBSERVACIONES');
    }

    public function getResponsable()
    {
        return $this -> getAttribute('DETA_RESPONSABLE');
    }

    public function getEntregoNombre()
    {
        return $this -> getAttribute('DETA_ENTREGO_NOMBRE');
    }

    public function getEntregoEmail()
    {
        return $this -> getAttribute('DETA_ENTREGO_EMAIL');
    }

    public function getEntregoTelefono()
    {
        return $this -> getAttribute('DETA_ENTREGO_TELEFONO');
    }

    public function getEntregoIdentificacion()
    {
        return $this -> getAttribute('DETA_ENTREGO_IDENTIFICACION');
    }

    /* Relationships */

	public function Municipio()
    {
        return $this -> hasOne('App\Model\MMunicipio', 'MUNI_MUNICIPIO', 'DETA_MUNICIPIO');
    }

    /* Presenter */    
    public function presenter(){
        return new MDetallePresenter($this);
    }

}