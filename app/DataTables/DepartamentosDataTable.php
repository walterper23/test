<?php

namespace App\DataTables;

use App\Model\Catalogo\MDepartamento;

class DepartamentosDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this->sourceData = MDepartamento::select(['DEPA_DEPARTAMENTO','DEPA_NOMBRE','DEPA_CREATED_AT','DEPA_ENABLED'])
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
                'title' => 'Dirección',
                'render' => function($query){
                    return 'Nombre de la direccion';
                }
            ],
            [
                'title' => 'opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-eye"></i></button>';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-pencil"></i></button>';
                
                    if($query->TIDO_ENABLED == 1){
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-level-down"></i></button>';
                    }else{
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.enabled('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-level-up"></i></button>';
                    }

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->DEPA_DEPARTAMENTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/departamentos/post-data');
    }
}
