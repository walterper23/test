<?php

namespace App\DataTables;

use App\Model\Catalogo\MAnexo;

class AnexosDataTable extends CustomDataTable
{
    public function setSourceData()
    {
        $this->sourceData = MAnexo::siExistente();
    }

    public function columnsTable()
    {
        return [
            [
                'title' => '#',
                'render' => function($anexo){
                    return $anexo->getCodigo();
                }
            ],
            [
                'title' => 'Nombre',
                'data'  => 'ANEX_NOMBRE'
            ],
            [
                'title' => 'Fecha',
                'data'  => 'ANEX_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($anexo){
                    $checked = $anexo->disponible() ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hAnexo.active({id:%d})"><span class="css-control-indicator"></span></label>',$checked,$anexo->getKey());
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($anexo){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hAnexo.view(%d)"><i class="fa fa-eye"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hAnexo.edit_(%d)"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hAnexo.delete_(%d)"><i class="fa fa-trash"></i></button>',
                        $anexo->getKey(), $anexo->getKey(), $anexo->getKey()
                    );
                    
                    return $buttons;
                }
            ]
        ];
    }

    public function getUrlAjax()
    {
        return '/configuracion/catalogos/anexos/post-data';
    }

}
