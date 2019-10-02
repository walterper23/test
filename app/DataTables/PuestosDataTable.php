<?php

namespace App\DataTables;

use App\Model\Catalogo\MPuesto;

class PuestosDataTable extends CustomDataTable
{
    public function setSourceData()
    {
        $this->sourceData = MPuesto::with('Direccion','Departamento')->siExistente();
    }

    public function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return sprintf('<b>%s</b>',$query->getCodigo());
                }
            ],
            [
                'title' => 'Nombre',
                'width' => '26%',
                'data'  => 'PUES_NOMBRE'
            ],
            [
                'title'  => 'Dirección',
                'width'  => '26%',
                'render' => function($query){
                    return $query->Direccion->getNombre();
                }
            ],
            [
                'title'  => 'Departamento',
                'width'  => '26%',
                'render' => function($query){
                    if( $query->Departamento != null ){
                        return $query->Departamento->getNombre();
                    }
                    return '<p class="font-size-xs text-muted">- Ninguno -</p>';
                }
            ],
            [
                'title' => 'Activo',
                'config' => 'options',
                'render' => function($query){
                    $checked = $query->disponible() ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hPuesto.active({id:%d})"><span class="css-control-indicator"></span></label>',$checked,$query->getKey());
                }
            ],
            [
                'title' => 'Opciones',
                'config' => 'options',
                'render' => function($query){
                    
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hPuesto.view(%d)"><i class="fa fa-eye"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hPuesto.edit_(%d)"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hPuesto.delete_(%d)"><i class="fa fa-trash"></i></button>',
                        $query->getKey(), $query->getKey(), $query->getKey()
                    );
                    
                    return $buttons;
                }
            ]
        ];
    }

    public function getUrlAjax()
    {
        return url('configuracion/catalogos/puestos/post-data');
    }

}
