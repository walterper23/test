<?php
namespace App\Model;

class MDenuncia extends BaseModel
{
	protected $table        = 'denuncias';
	protected $primaryKey   = 'DENU_DENUNCIA';
	protected $prefix       = 'DENU';

	/* Methods */

	public function getDocumento()
	{
		return $this->getAttribute('DENU_DOCUMENTO');
	}

	public function getNoExpediente()
	{
		return $this->getAttribute('DENU_NO_EXPEDIENTE');
	}

	/* Relationships */

	public function Documento()
	{
		return $this->belongsTo('App\Model\MDocumento','DENU_DOCUMENTO','DOCU_DOCUMENTO');
	}

}