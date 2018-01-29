<?php

namespace App\DataTables;

use App\Model\Catalogo\MPuesto;

class PuestosDataTable extends CustomDataTable{
    
    protected function setSourceData(){
        $this->sourceData = MPuesto::with(['direccion','departamento'])->select(['PUES_PUESTO','PUES_DIRECCION','PUES_DEPARTAMENTO','PUES_NOMBRE','PUES_CREATED_AT','PUES_ENABLED'])->where('PUES_DELETED',0)->get();
    }

    protected function columnsTable(){
        return [
            /*[
                'title'  => '#',
                'render' => function($query){
                    return '<div class="custom-controls-stacked">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="'.$query->PUES_PUESTO.'">
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>';
                },
                'searchable' => false,
                'orderable'  => false,
            ],*/
            [
                'title' => 'Nombre',
                'data' => 'PUES_NOMBRE'
            ],
            [
                'title' => 'Dirección',
                'render' => function($query){
                    if( $query->direccion != null ){
                        return $query->direccion->presenter()->link();
                    }
                    return '- Ninguno -';
                }
            ],
            [
                'title' => 'Departamento',
                'render' => function($query){
                    if( $query->departamento != null ){
                        return $query->departamento->presenter()->link();
                    }
                    return '- Ninguno -';
                }
            ],
            [
                'title' => 'Fecha / Hora',
                'data' => 'PUES_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = '';
                    if($query->PUES_ENABLED == 1){
                        $checked = 'checked=""';
                    }
                    return '<label class="css-control css-control-sm css-control-primary css-switch">
                                <input type="checkbox" class="css-control-input" '.$checked.' onclick="hPuesto.active({id:'.$query->PUES_PUESTO.'})"><span class="css-control-indicator"></span>
                            </label>';
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hPuesto.edit_('.$query->PUES_PUESTO.')"><i class="fa fa-pencil"></i></button>';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hPuesto.delete_('.$query->PUES_PUESTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/puestos/post-data');
    }

}
