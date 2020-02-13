<?php

namespace App\DataTables;

use App\Model\System\MSystemEstadoDocumento;

class SystemEstadosDocumentosDataTable extends CustomDataTable
{
    public function setSourceData()
    {
        $this->sourceData = MSystemEstadoDocumento::select('SYED_ESTADO_DOCUMENTO','SYED_NOMBRE','SYED_CREATED_AT');
    }

    public function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'class' => 'text-center',
                'render' => function($estado){
                    return sprintf('<b>%s</b>',$estado->getKey());
                }
            ],
            [
                'title' => 'Nombre',
                'width' => '70%',
                'data'  => 'SYED_NOMBRE'
            ],
            [
                'title' => 'Fecha',
                'class' => 'text-center',
                'data'  => 'SYED_CREATED_AT'
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options', 
                'render' => function($estado){
                    return sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hSistemaEstadoDocumento.view(%d)"><i class="fa fa-eye"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hSistemaEstadoDocumento.edit_(%d)"><i class="fa fa-pencil"></i></button>', $estado->getKey(), $estado->getKey() );
                }
            ]

        ];
    }

    public function getUrlAjax()
    {
        return url('configuracion/sistema/estados-documentos/post-data');
    }
    
}
