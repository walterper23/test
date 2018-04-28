<?php
namespace App\Model;

class MAcuseRecepcion extends BaseModel
{
	protected $table        = 'acuses_recepcion';
	protected $primaryKey   = 'ACUS_ACUSE';
	protected $prefix       = 'ACUS';
	
	/* Methods */

	public function getNombre()
	{
		return $this -> attributes['ACUS_NOMBRE'];
	}

	public function getNumero()
	{
		return $this -> attributes['ACUS_NUMERO'];
	}

	public function getCaptura()
	{
		return $this -> attributes['ACUS_CAPTURA'];
	}

	/* Relationships */

	public function Detalle()
	{
		return $this -> belongsTo('App\Model\MDetalle','ACUS_DETALLE','DETA_DETALLE');
	}	

	public function DocumentoLocal()
	{
		return $this -> belongsTo('App\Model\MDocumento','ACUS_DOCUMENTO','DOCU_DOCUMENTO');
	}

	public function DocumentoForaneo()
	{
		return $this -> belongsTo('App\Model\MDocumentoForaneo','ACUS_DOCUMENTO','DOFO_DOCUMENTO');
	}

	public function Usuario()
	{
		return $this -> belongsTo('App\Model\MUsuario','ACUS_USUARIO','USUA_USUARIO');
	}

}