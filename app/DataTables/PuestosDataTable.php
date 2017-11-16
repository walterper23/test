<?php

namespace App\DataTables;

use App\Model\Catalogo\MPuesto;

class PuestosDataTable extends CustomDataTable{
    
    protected function setSourceData(){
        $this->sourceData = MPuesto::with('departamento')->select(['PUES_PUESTO','PUES_DEPARTAMENTO','PUES_NOMBRE','PUES_CREATED_AT','PUES_ENABLED'])
                            ->where('PUES_DELETED',0);
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return '<div class="custom-controls-stacked">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->PUES_PUESTO.'">
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>';
                },
                'searchable' => false,
                'orderable'  => false,
            ],
            [
                'title' => 'Nombre',
                'data' => 'PUES_NOMBRE'
            ],
            [
                'title' => 'Departamento',
                'render' => function($query){
                    return $query->departamento->presenter()->link();
                }
            ],
            [
                'title' => 'Fecha',
                'data' => 'PUES_CREATED_AT'
            ],
            [
                'title' => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->PUES_PUESTO.')"><i class="fa fa-eye"></i></button>';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->PUES_PUESTO.')"><i class="fa fa-pencil"></i></button>';
                
                    if($query->PUES_ENABLED == 1){
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->PUES_PUESTO.')"><i class="fa fa-level-down"></i></button>';
                    }else{
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.enabled('.$query->PUES_PUESTO.')"><i class="fa fa-level-up"></i></button>';
                    }

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->PUES_PUESTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/puestos/post-data');
    }

}
