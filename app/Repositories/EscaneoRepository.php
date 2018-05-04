<?php
namespace App\Repositories;

use App\Model\MEscaneo;

class EscaneoRepository extends BaseRepository
{
	public function __construct(MEscaneo $model)
	{
		parent::__construct($model);
	}


}