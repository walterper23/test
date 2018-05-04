<?php
namespace App\Repositories;

use App\Model\Catalogo\MAnexo;

class AnexoRepository extends BaseRepository
{
	public function __construct(MAnexo $model)
	{
		parent::__construct($model);
	}


}