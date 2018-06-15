<?php
namespace App\Model;

class MSystemNotificacion extends BaseModel
{
	protected $table        = 'system_notificaciones';
	protected $primaryKey   = 'SYNO_NOTIFICACION';
	protected $prefix       = 'SYNO';
	
	/* Methods */

    public function getCodigo( $size = 3, $str = '0', $direction = STR_PAD_LEFT )
	{
		return $this -> getAttribute('SYNO_CODIGO');
	}

	public function getColor()
	{
		return $this -> getAttribute('SYNO_COLOR');
	}

	public function getNombre()
	{
		return $this -> getAttribute('SYNO_NOMBRE');
	}

	public function getDescripcion()
	{
		return $this -> getAttribute('SYNO_DESCRIPCION');
	}

	public function getPermiso()
	{
		return $this -> getAttribute('SYNO_SYSTEM_PERMISO');
	}

	public function getTipo()
	{
		return $this -> getAttribute('SYNO_SYSTEM_TIPO');
	}

	/* Relationships */

	public function Permiso()
	{
		return $this -> belongsTo('App\Model\MPermiso','SYNO_SYSTEM_PERMISO','SYPE_PERMISO');
	}

	public function TipoNotificacion()
	{
		return $this -> belongsTo('App\Model\MSystemTipoNotificacion','SYNO_SYSTEM_TIPO','SYTN_TIPO');
	}

}