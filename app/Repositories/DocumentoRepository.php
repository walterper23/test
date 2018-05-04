<?php
namespace App\Repositories;

use App\Model\MDocumento;

class DocumentoRepository extends BaseRepository
{
	public function __construct(MDocumento $model)
	{
		parent::__construct($model);
	}



	public function getDocumentosByFecha()
	{



	}

}