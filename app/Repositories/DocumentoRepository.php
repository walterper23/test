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
        $this -> records = $this -> model
                    -> leftJoin('system_tipos_documentos','SYTD_TIPO_DOCUMENTO','=','DOCU_SYSTEM_TIPO_DOCTO')
                    -> leftJoin('system_estados_documentos','SYED_ESTADO_DOCUMENTO','=','DOCU_SYSTEM_ESTADO_DOCTO')
                    -> leftJoin('detalles','DETA_DETALLE','=','DOCU_DETALLE')
                    -> leftJoin('documentos_foraneos','DOFO_DOCUMENTO_LOCAL','=','DOCU_DOCUMENTO')
                    //-> whereYear('DETA_FECHA_RECEPCION',$anio_actual)
                    -> existente()
                    -> get();
        return $this;
    }

    public function filterByTipo($tipos)
    {

        return $this;
    }

    public function groupByTipo()
    {
        $this -> records = $this -> records -> groupBy('SYTD_TIPO_DOCUMENTO');
        return $this;
    }

    public function groupByRecepcion()
    {

        return $this;
    }

    public function filterByForaneos()
    {

        return $this;
    }

    public function filterByEstatus($status)
    {

        return $this;
    }

    public function filterByFechas( $fecha1, $fecha2 )
    {


        return $this;
    }



    public function crearNuevo( $data )
    {
        switch (true) {
            case isset($data['']):

                break;
            case isset($data['']):
            
                break;
            case isset($data['']):
            
                break;
            case isset($data['']):
            
                break;
            case isset($data['']):
            
                break;
            case isset($data['']):
            
                break;
            case isset($data['']):
            
                break;
            case isset($data['']):
            
                break;
            
            default:
                break;
        }
        $this -> save();
    }


}