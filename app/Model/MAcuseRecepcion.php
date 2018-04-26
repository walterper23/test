<?php
namespace App\Model;

/* Presenter */

class MAcuseRecepcion extends BaseModel
{
	protected $table        = 'acuses_recepcion';
	protected $primaryKey   = 'ACUS_ACUSE';
	protected $prefix       = 'ACUS';
	
	/* Methods */

	public function getNumero()
	{
		return $this -> attributes['ACUS_NUMERO'];
	}

	/* Relationships */

	public function Detalle()
	{
		return $this -> belongsTo('App\Model\MDetalle','ACUS_DETALLE','DETA_DETALLE');
	}	

	public function DocumentoLocal()
	{
		return $this -> belongsTo('App\Model\MDocumento','ACUS_DOCUMENTO_LOCAL','DOCU_DOCUMENTO');
	}

	public function DocumentoForaneo()
	{
		return $this -> belongsTo('App\Model\MDocumentoForaneo','ACUS_DOCUMENTO_FORANEO','DOFO_DOCUMENTO');
	}

	public function Usuario()
	{
		return $this -> belongsTo('App\Model\MUsuario','ACUS_USUARIO','USUA_USUARIO');
	}

}