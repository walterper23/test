<?php

namespace App\DataTables;

use App\Model\System\MSystemTipoDocumento;

class SystemTiposDocumentosDataTable extends CustomDataTable
{
    public function setSourceData()
    {
        $this->sourceData = MSystemTipoDocumento::select('SYTD_TIPO_DOCUMENTO','SYTD_NOMBRE','SYTD_ETIQUETA_NUMERO','SYTD_RIBBON_COLOR','SYTD_CREATED_AT','SYTD_ENABLED')->existente()->orderBy('SYTD_TIPO_DOCUMENTO','ASC');
    }

    public function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'render' => function($tipoDocumento){
                    return $tipoDocumento->getCodigo();
                }
            ],
            [
                'title' => 'Nombre',
                'data'  => 'SYTD_NOMBRE'
            ],
            [
                'title' => 'Solicitar',
                'data'  => 'SYTD_ETIQUETA_NUMERO'
            ],
            [
                'title'  => 'Color',
                'render' => function($tipoDocumento){
                    return $tipoDocumento->presenter()->getBadge();
                } 
            ],
            [
                'title' => 'Fecha',
                'data'  => 'SYTD_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($tipoDocumento){
                    $checked = $tipoDocumento->disponible() ? 'checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                                <input type="checkbox" class="css-control-input" %s onclick="hSistemaTipoDocumento.active({id:%d})"><span class="css-control-indicator"></span>
                            </label>',$checked,$tipoDocumento->getKey());
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($tipoDocumento){

                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hSistemaTipoDocumento.view(%d)"><i class="fa fa-eye"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hSistemaTipoDocumento.edit_(%d)"><i class="fa fa-pencil"></i></button>', $tipoDocumento->getKey(), $tipoDocumento->getKey());

                    if ( $tipoDocumento->getKey() > 2 ){ // Diferente de Denuncia y Documento para denuncia
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hSistemaTipoDocumento.delete_(%d)"><i class="fa fa-trash"></i></button>', $tipoDocumento->getKey());
                    }

                    return $buttons;
                }
            ]

        ];
    }

    public function getUrlAjax()
    {
        return url('configuracion/sistema/tipos-documentos/post-data');
    }
    
}
