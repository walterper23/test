<?php
namespace App\Repositories;

use App\Model\MDocumentoForaneo;

class DocumentoForaneoRepository extends BaseRepository
{
	public function __construct(MDocumentoForaneo $model)
	{
		parent::__construct($model);
	}



	public function getDocumentosByFecha()
	{



	}

}