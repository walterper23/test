<?php
namespace App\DataTables;

use App\Model\Catalogo\MPuesto;

class PuestosDataTable extends CustomDataTable{
    
    protected function setSourceData(){
        $this->sourceData = MPuesto::with(['Direccion','Departamento']) -> select(['PUES_PUESTO','PUES_DIRECCION','PUES_DEPARTAMENTO','PUES_NOMBRE','PUES_CREATED_AT','PUES_ENABLED']) -> where('PUES_DELETED',0);
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
                'data' => 'PUES_NOMBRE'
            ],
            [
                'title' => 'Dirección',
                'render' => function($query){
                    return $query -> Direccion -> presenter() -> link();
                }
            ],
            [
                'title' => 'Departamento',
                'render' => function($query){
                    if( $query -> Departamento != null ){
                        return $query -> Departamento -> presenter() -> link();
                    }
                    return '- Ninguno -';
                }
            ],
            [
                'title' => 'Fecha',
                'data' => 'PUES_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = $query -> disponible() ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hPuesto.active({id:%d})"><span class="css-control-indicator"></span></label>',$checked,$query -> getKey());
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($query){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hPuesto.view(%d)"><i class="fa fa-eye"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hPuesto.edit_(%d)"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hPuesto.delete_(%d)"><i class="fa fa-trash"></i></button>',
                        $query -> getKey(), $query -> getKey(), $query -> getKey()
                    );
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/puestos/post-data');
    }

}