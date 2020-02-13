<?php
namespace App\Model;

class MUsuarioPreferencia extends BaseModel
{
	protected $table        = 'usuarios_preferencias';
	protected $primaryKey   = 'USPR_USUARIO_PREFERENCIA';
	protected $prefix       = 'USPR';
	
	/* Methods */
	public function getUsuario()
	{
		return $this->getAttribute('USPR_USUARIO');
	}

	public function getPreferencia()
	{
		return $this->getAttribute('USPR_PREFERENCIA');
	}

	/* Relationships */

	public function Usuario()
	{
		return $this->belongsTo('App\Model\MUsuario','USPR_USUARIO','USUA_USUARIO');
	}

	public function Preferencia()
	{
		return $this->belongsTo('App\Model\System\MSystemPreferencia','USPR_PREFERENCIA','SYPR_PREFERENCIA');
	}

}