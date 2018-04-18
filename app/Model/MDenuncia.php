<?php
namespace App\Model;

/* Models */
use App\Model\BaseModel;

class MDenuncia extends BaseModel
{
    
	protected $table        = 'denuncias';
	protected $primaryKey   = 'DENU_DENUNCIA';
	protected $prefix       = 'DENU';


	/* Methods */

	public function getNoExpediente()
	{
		return $this -> attributes['DENU_NO_EXPEDIENTE'];
	}

	/* Relationships */

	public function Documento()
	{
		return $this -> belongsTo('App\Model\MDocumento','DENU_DOCUMENTO','DOCU_DOCUMENTO');
	}

}