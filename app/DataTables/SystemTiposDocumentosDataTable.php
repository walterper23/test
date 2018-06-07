<?php
namespace App\DataTables;

use App\Model\System\MSystemTipoDocumento;

class SystemTiposDocumentosDataTable extends CustomDataTable
{

    protected function setSourceData()
    {
        $this -> sourceData = MSystemTipoDocumento::select('SYTD_TIPO_DOCUMENTO','SYTD_NOMBRE','SYTD_CREATED_AT','SYTD_ENABLED')
                                -> where('SYTD_DELETED',0) -> orderBy('SYTD_TIPO_DOCUMENTO','ASC');
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
                'data'  => 'SYTD_NOMBRE'
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
                                <input type="checkbox" class="css-control-input" %s onclick="hSistemaTipoDocumento.active({id:%d})"><span class="css-control-indicator"></span>
                            </label>',$checked,$query -> getKey());
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){

                    $buttons = sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hSistemaTipoDocumento.edit_(%d)"><i class="fa fa-pencil"></i></button>', $query -> getKey());

                    if ( $query -> getKey() != 1 )
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hSistemaTipoDocumento.delete_(%d)"><i class="fa fa-trash"></i></button>', $query -> getKey());


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