<?php
namespace App\Repositories;

use App\Model\Catalogo\MEstadoDocumento;

class EstadoDocumentoRepository extends BaseRepository
{
	public function __construct(MEstadoDocumento $model)
	{
		parent::__construct($model);
	}


}