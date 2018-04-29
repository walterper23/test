<?php
namespace App\Presenters;

class MDetallePresenter extends Presenter
{

	public function getFechaHora( $format = 'Y-m-d h:i:s a' )
	{
		$created_at = $this -> model -> DETA_CREATED_AT;
		
		if (! is_null($format))
		{
			return date($format,strtotime($created_at));
		}

		return $created_at;
	}

}