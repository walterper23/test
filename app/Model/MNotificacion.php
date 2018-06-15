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

	public function getFechaCreacion()
	{
		return $this -> getAttribute('NOTI_CREATED_AT');
	}

	public function getPermiso()
	{
		return $this -> getAttribute('NOTI_SYSTEM_PERMISO');
	}

	public function getTipo()
	{
		return $this -> getAttribute('NOTI_SYSTEM_TIPO');
	}
	
	public function getUrl()
	{
		return $this -> getAttribute('NOTI_URL');
	}

	/* Relationships */

	public function Permiso()
	{
		return $this -> belongsTo('App\Model\MPermiso','NOTI_SYSTEM_PERMISO','SYPE_PERMISO');
	}

	public function TipoNotificacion()
	{
		return $this -> belongsTo('App\Model\MSystemTipoNotificacion','NOTI_SYSTEM_TIPO','SYTN_TIPO');
	}

}