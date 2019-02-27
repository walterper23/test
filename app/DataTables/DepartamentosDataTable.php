<?php
namespace App\DataTables;

use App\Model\Catalogo\MDepartamento;

class DepartamentosDataTable extends CustomDataTable
{
    protected function setSourceData(){
        $this->sourceData = MDepartamento::with('direccion')->select(['DEPA_DEPARTAMENTO','DEPA_DIRECCION','DEPA_NOMBRE','DEPA_CREATED_AT','DEPA_ENABLED'])->existente()->get();
    }

    protected function columnsTable(){
        return [
            [
                'title' => '#',
                'render' => function($departamento){
                    return $departamento->getCodigo();
                }
            ],
            [
                'title' => 'Nombre',
                'data' => 'DEPA_NOMBRE'
            ],
            [
                'title' => 'Dirección',
                'render' => function($departamento){
                    return $departamento->Direccion->getNombre();
                }
            ],
            [
                'title' => 'Fecha',
                'data'  => 'DEPA_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($departamento){
                    $checked = ($departamento->disponible()) ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hDepartamento.active({id:%d})"><span class="css-control-indicator"></span></label>',$checked,$departamento->getKey());
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($departamento){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hDepartamento.view(%d)"><i class="fa fa-eye"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hDepartamento.edit_(%d)"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hDepartamento.delete_(%d)"><i class="fa fa-trash"></i></button>',
                        $departamento->getKey(), $departamento->getKey(), $departamento->getKey()
                    );
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/departamentos/post-data');
    }
}
