<?php
namespace App\Repositories;

use App\Model\MDocumento;

class DocumentoRepository implements InterfaceRepository
{

	protected $model;

	public function __construct(MDocumento $model)
	{
		$this -> model = $model
	}

}