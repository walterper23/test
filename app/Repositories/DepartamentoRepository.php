<?php
namespace App\Repositories;

use App\Model\Catalogo\MDepartamento;

class DepartamentoRepository extends BaseRepository
{
	public function __construct(MDepartamento $model)
	{
		parent::__construct($model);
	}


}