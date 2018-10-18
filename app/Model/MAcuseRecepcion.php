<?php
namespace App\Model;

class MAcuseRecepcion extends BaseModel
{
	protected $table        = 'acuses_recepcion';
	protected $primaryKey   = 'ACUS_ACUSE';
	protected $prefix       = 'ACUS';
	
	/* Methods */

	public function getDetalle()
	{
		return $this->getAttribute('ACUS_DETALLE');
	}

	public function getCaptura()
	{
		return $this->getAttribute('ACUS_CAPTURA');
	}

	public function getEntrego()
	{
		return $this->getAttribute('ACUS_ENTREGO');
	}
	
	public function getRecibio()
	{
		return $this->getAttribute('ACUS_RECIBIO');
	}

	public function getNombre()
	{
		return $this->getAttribute('ACUS_NOMBRE');
	}

	public function getNumero()
	{
		return $this->getAttribute('ACUS_NUMERO');
	}

	public function getUsuario()
	{
		return $this->getAttribute('ACUS_USUARIO');
	}


	/* Relationships */

	public function Detalle()
	{
		return $this->belongsTo('App\Model\MDetalle','ACUS_DETALLE','DETA_DETALLE');
	}	

	public function DocumentoLocal()
	{
		return $this->belongsTo('App\Model\MDocumento','ACUS_DOCUMENTO','DOCU_DOCUMENTO');
	}

	public function DocumentoForaneo()
	{
		return $this->belongsTo('App\Model\MDocumentoForaneo','ACUS_DOCUMENTO','DOFO_DOCUMENTO');
	}

	public function Usuario()
	{
		return $this->belongsTo('App\Model\MUsuario','ACUS_USUARIO','USUA_USUARIO');
	}

}