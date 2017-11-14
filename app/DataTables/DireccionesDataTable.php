<?php

namespace App\DataTables;

use App\Model\Catalogo\MDireccion;

class DireccionesDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this->sourceData = MDireccion::selectRaw('DIRE_DIRECCION, DIRE_NOMBRE, DIRE_CREATED_AT, DIRE_ENABLED')
                            ->where('DIRE_DELETED',0)->get();
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return '<div class="custom-controls-stacked">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->DIRE_DIRECCION.'">
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>';
                },
                'searchable' => false,
                'orderable'  => false,
            ],
            [
                'title' => 'Nombre',
                'data'  => 'DIRE_NOMBRE'
            ],
            [
                'title' => 'Fecha',
                'data'  => 'DIRE_CREATED_AT'
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->DIRE_DIRECCION.')"><i class="fa fa-eye"></i></button>';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->DIRE_DIRECCION.')"><i class="fa fa-pencil"></i></button>';
                
                    if($query->TIDO_ENABLED == 1){
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->DIRE_DIRECCION.')"><i class="fa fa-level-down"></i></button>';
                    }else{
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.enabled('.$query->DIRE_DIRECCION.')"><i class="fa fa-level-up"></i></button>';
                    }

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->DIRE_DIRECCION.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]

        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/direcciones/post-data');
    }
}
