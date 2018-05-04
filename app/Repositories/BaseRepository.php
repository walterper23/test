<?php
namespace App\Repositories;

use namespace Illuminate\Database\Eloquent\Model;

class BaseRepository implements InterfaceRepository
{
	protected $model;

	public function __construct(Model $model)
	{
		$this -> model = $model;
	}


	public function toArray()
	{

	}

}