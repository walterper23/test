<?php
namespace App\Model;

/* Models */
use Illuminate\Database\Eloquent\Model;

class MAnexo extends Model
{
    
	protected $table        = 'documentos_marcadores';
	protected $primaryKey   = 'DOMA_MARCADOR';
	public    $timestamps   = false;
	

	public function Documento()
	{
		return $this -> belongsTo('App\Model\MDocumento','DOMA_DOCUMENTO','DOCU_DOCUMENTO');
	}

}