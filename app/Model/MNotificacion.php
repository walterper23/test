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
		return $this -> attributes['NOTI_COLOR'];
	}

	public function getContenido()
	{
		return $this -> attributes['NOTI_CONTENIDO'];
	}

	public function getUrl()
	{
		return $this -> attributes['NOTI_URL'];
	}

	public function getFechaCreacion()
	{
		return $this -> attributes['NOTI_CREATED_AT'];
	}


	/* Relationships */

	public function Permiso()
	{
		return $this -> belongsTo('App\Model\MPermiso','NOTI_PERMISO','SYPE_PERMISO');
	}

}