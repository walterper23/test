<?php
namespace App\Repositories;

use App\Model\Catalogo\MDenuncia;

class DenunciaRepository extends BaseRepository
{
	public function __construct(MDenuncia $model)
	{
		parent::__construct($model);
	}


}