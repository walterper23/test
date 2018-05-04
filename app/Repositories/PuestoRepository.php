<?php
namespace App\Repositories;

use App\Model\Catalogo\MPuesto;

class PuestoRepository extends BaseRepository
{
	public function __construct(MPuesto $model)
	{
		parent::__construct($model);
	}


}