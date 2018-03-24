<?php
namespace App\DataTables;

use App\Model\Sistema\MSistemaTipoDocumento;

class SistemaTiposDocumentosDataTable extends CustomDataTable
{

    protected function setSourceData()
    {
        $this -> sourceData = MSistemaTipoDocumento::select('SYTD_TIPO_DOCUMENTO','SYTD_NOMBRE_TIPO','SYTD_CREATED_AT','SYTD_ENABLED')
                            ->orderBy('SYTD_CREATED_AT','DESC');
    }

    protected function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return $query -> getCodigo();
                }
            ],
            [
                'title' => 'Nombre',
                'data'  => 'SYTD_NOMBRE_TIPO'
            ],
            [
                'title' => 'Fecha',
                'data'  => 'SYTD_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = $query -> disponible() ? 'checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                                <input type="checkbox" class="css-control-input" %s onclick="hTipoDocumento.active({id:%d})"><span class="css-control-indicator"></span>
                            </label>',$checked,$query -> getKey());
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hTipoDocumento.edit_(%d)"><i class="fa fa-pencil"></i></button>', $query -> getKey() );
                    
                    return $buttons;
                }
            ]

        ];
    }

    protected function getUrlAjax()
    {
        return url('configuracion/sistema/tipos-documentos/post-data');
    }
    
}