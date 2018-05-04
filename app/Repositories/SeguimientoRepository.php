<?php
namespace App\Repositories;

use App\Model\MSeguimiento;

class SeguimientoRepository extends BaseRepository
{
	public function __construct(MSeguimiento $model)
	{
		parent::__construct($model);
	}


}