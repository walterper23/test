<?php
namespace App\Repositories;

use App\Model\MDocumento;

class DocumentoRepository extends BaseRepository
{
	public function __construct(MDocumento $model)
	{
		parent::__construct($model);
	}

	public function getReporteDocumentos()
	{
		$this -> records = $this -> model -> leftJoin('documentos_foraneos','DOFO_DOCUMENTO_LOCAL','=','DOCU_DOCUMENTO')
					        -> leftJoin('detalles','DETA_DETALLE','=','DOCU_DETALLE')
					        -> whereYear('DETA_FECHA_RECEPCION',$anio_actual)
					        -> existente()
					        -> get();
	}


	public function filterByFechas( $fecha1, $fecha2 )
	{



	}

}