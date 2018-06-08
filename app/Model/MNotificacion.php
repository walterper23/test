<?php
namespace App\Model;

class MNotificacion extends BaseModel
{
	protected $table        = 'notificaciones';
	protected $primaryKey   = 'NOTI_NOTIFICACION';
	protected $prefix       = 'NOTI';
	

	/* Methods */

	public function getColor()
	{
		return $this -> getAttribute('NOTI_COLOR');
	}

	public function getContenido()
	{
		return $this -> getAttribute('NOTI_CONTENIDO');
	}

	public function getUrl()
	{
		return $this -> getAttribute('NOTI_URL');
	}

	public function getFechaCreacion()
	{
		return $this -> getAttribute('NOTI_CREATED_AT');
	}


	/* Relationships */

	public function Permiso()
	{
		return $this -> belongsTo('App\Model\MPermiso','NOTI_PERMISO','SYPE_PERMISO');
	}

}