<?php
namespace App\Model;

class MNotificacionArea extends BaseModel
{
	protected $table        = 'notificaciones_areas';
	protected $primaryKey   = 'NOAR_NOTI_AREA';
	protected $prefix       = 'NOAR';
	
	/* Methods */
	public function getDepartamento()
	{
		return $this -> getAttribute('NOAR_DEPARTAMENTO');
	}

	public function getDireccion()
	{
		return $this -> getAttribute('NOAR_DIRECCION');
	}

	public function getNotificacion()
	{
		return $this -> getAttribute('NOAR_NOTIFICACION');
	}

	/* Relationships */

	public function Departamento()
	{
		return $this -> belongsTo('App\Model\MDepartamento','NOAR_DEPARTAMENTO','DEPA_DEPARTAMENTO');
	}

	public function Direccion()
	{
		return $this -> belongsTo('App\Model\MDireccion','NOAR_DIRECCION','DIRE_DIRECCION');
	}

	public function Notificacion()
	{
		return $this -> hasOne('App\Model\MNotificacion','NOTI_NOTIFICACION','NOAR_NOTIFICACION');
	}

}