<?php
namespace App\Presenters;

use Illuminate\Database\Eloquent\Model;

abstract class Presenter
{
	protected $model;

	public function __construct(Model $model){
		$this -> model = $model;
	}



	public function getFechaCreacion( $format = 'Y-m-d h:i:s a' )
	{
		$fieldCreatedAt = $this -> model -> getField('CREATED_AT');
		$fieldCreatedAt = $this -> model -> getAttribute($fieldCreatedAt);
		
		if (! is_null($format))
		{
			return date($format,strtotime($fieldCreatedAt));
		}

		return $fieldCreatedAt;
	}
}