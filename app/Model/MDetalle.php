<?php
namespace App\Model;

class MDetalle extends BaseModel
{
	protected $table        = 'detalles';
	protected $primaryKey   = 'DETA_DETALLE';
    protected $prefix       = 'DETA';

    /* Methods */

    public function getAnexos()
    {
        return $this -> attributes['DETA_ANEXOS'];
    }
    
    public function getDescripcion()
    {
        return $this -> attributes['DETA_DESCRIPCION'];
    }
    
    public function getFechaRecepcion()
    {
        return $this -> attributes['DETA_FECHA_RECEPCION'];
    }

    public function getObservaciones()
    {
        return $this -> attributes['DETA_OBSERVACIONES'];
    }

    public function getResponsable()
    {
        return $this -> attributes['DETA_RESPONSABLE'];
    }

    /* Relationships */

	public function Municipio()
    {
        return $this -> hasOne('App\Model\MMunicipio', 'MUNI_MUNICIPIO', 'DETA_MUNICIPIO');
    }

}