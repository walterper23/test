<?php
namespace App\Repositories;

use App\Model\MDocumentoSemaforizado;

class DocumentoSemaforizadoRepository extends BaseRepository
{
	public function __construct(MDocumentoSemaforizado $model)
	{
		parent::__construct($model);
	}


}