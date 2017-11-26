<?php

namespace App\DataTables;

use App\Model\Catalogo\MDepartamento;

class DepartamentosDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this->sourceData = MDepartamento::with('direccion')->select(['DEPA_DEPARTAMENTO','DEPA_DIRECCION','DEPA_NOMBRE','DEPA_CREATED_AT','DEPA_ENABLED'])
                            ->where('DEPA_DELETED',0);
    }

    protected function columnsTable(){
        return [
            [
                'title' => '#',
                'render' => function($query){
                    return '<div class="custom-controls-stacked">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->DEPA_DEPARTAMENTO.'">
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>';
                }
            ],
            [
                'title' => 'Nombre',
                'data' => 'DEPA_NOMBRE'
            ],
            [
                'title' => 'Dirección',
                'render' => function($query){
                    return $query->direccion->presenter()->link();
                }
            ],
            [
                'title' => 'Fecha',
                'data'  => 'DEPA_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = '';
                    if($query->DEPA_ENABLED == 1){
                        $checked = 'checked=""';
                    }
                    return '<label class="css-control css-control-sm css-control-primary css-switch">
                                <input type="checkbox" class="css-control-input" '.$checked.' onclick="hDepartamento.active({id:'.$query->DEPA_DEPARTAMENTO.'})"><span class="css-control-indicator"></span>
                            </label>';
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hDepartamento.edit('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-pencil"></i></button>';
                
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
