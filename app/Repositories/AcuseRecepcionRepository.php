<?php
namespace App\Repositories;

use App\Model\MAcuseRecepcion;

class AcuseRecepcionRepository extends BaseRepository
{
	public function __construct(MAcuseRecepcion $model)
	{
		parent::__construct($model);
	}


}