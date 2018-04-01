<?php
namespace App\Presenters;

class MSeguimientoPresenter extends Presenter
{

	public function getFechaSeguimiento( $format = 'Y-m-d h:i:s a' )
	{
		$created_at = $this -> model -> SEGU_CREATED_AT;
		
		if (! is_null($format))
		{
			return date($format,strtotime($created_at));
		}

		return $created_at;
	}

}