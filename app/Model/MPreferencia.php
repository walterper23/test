<?php
namespace App\Model;

class MPreferencia extends BaseModel
{
    
	protected $table        = 'preferencias';
	protected $primaryKey   = 'PREF_PREFERENCIA';
	protected $prefix       = 'PREF';
	
	/* Methods */



	/* Relationships */
	public function Usuarios()
    {
        return $this -> belongsToMany('App\Model\MUsuario','usuarios_preferencias','USPR_PREFERENCIA','USPR_USUARIO');
    }

}