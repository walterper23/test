<?php
namespace App\DataTables;

use App\Model\Catalogo\MDepartamento;

class DepartamentosDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this->sourceData = MDepartamento::with('direccion') -> select(['DEPA_DEPARTAMENTO','DEPA_DIRECCION','DEPA_NOMBRE','DEPA_CREATED_AT','DEPA_ENABLED'])
                            -> where('DEPA_DELETED',0);
    }

    protected function columnsTable(){
        return [
            [
                'title' => '#',
                'render' => function($query){
                    return $query -> getCodigo();
                }
            ],
            [
                'title' => 'Nombre',
                'data' => 'DEPA_NOMBRE'
            ],
            [
                'title' => 'Dirección',
                'render' => function($query){
                    return $query -> Direccion -> presenter() -> link();
                }
            ],
            [
                'title' => 'Fecha',
                'data'  => 'DEPA_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = ($query -> disponible()) ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hDepartamento.active({id:%d})"><span class="css-control-indicator"></span></label>',$checked,$query -> getKey());
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hDepartamento.edit_('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-pencil"></i></button>';
                
                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hDepartamento.delete_('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/departamentos/post-data');
    }
}
