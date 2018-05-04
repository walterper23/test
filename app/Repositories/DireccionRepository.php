<?php
namespace App\Repositories;

use App\Model\Catalogo\MDireccion;

class DireccionRepository extends BaseRepository
{
	public function __construct(MDireccion $model)
	{
		parent::__construct($model);
	}


}