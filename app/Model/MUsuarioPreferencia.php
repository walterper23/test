<?php
namespace App\Model;

class MUsuarioPreferencia extends BaseModel
{
	protected $table        = 'usuarios_preferencias';
	protected $primaryKey   = 'USPR_USUARIO_PREFERENCIA';
	protected $prefix       = 'USPR';
	
	/* Methods */



	/* Relationships */

	public function Usuario()
	{
		return $this -> belongsTo('App\Model\MUsuario','USPR_USUARIO','USUA_USUARIO');
	}

	public function Preferencia()
	{
		return $this -> belongsTo('App\Model\MPreferencia','USPR_PREFERENCIA','PREF_PREFERENCIA');
	}

    /* Presenter */    

}