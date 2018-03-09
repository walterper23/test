<?php
namespace App\DataTables;

use App\Model\Sistema\MSistemaEstadoDocumento;

class SistemaEstadosDocumentosDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this->sourceData = MSistemaEstadoDocumento::select('SYED_ESTADO_DOCUMENTO','SYED_NOMBRE','SYED_CREATED_AT');
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return $query -> getCodigo();
                }
            ],
            [
                'title' => 'Nombre',
                'data'  => 'SYED_NOMBRE'
            ],
            [
                'title' => 'Fecha',
                'data'  => 'SYED_CREATED_AT'
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hSistemaEstadoDocumento.edit_(%d)"><i class="fa fa-pencil"></i></button>', $query -> getKey() );
                    
                    return $buttons;
                }
            ]

        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/sistema/estados-documentos/post-data');
    }
    
}