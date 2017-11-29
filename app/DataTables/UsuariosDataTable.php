<?php

namespace App\DataTables;

use App\Model\MUsuario;

class UsuariosDataTable extends CustomDataTable{
    
    protected function setSourceData(){
        $this->sourceData = MUsuario::with('usuarioDetalle')->select(['USUA_USUARIO','USUA_CREATED_AT','USUA_ENABLED'])->
                            where('USUA_DELETED',0)->where('USUA_ENABLED',1);
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return '<div class="custom-controls-stacked">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="'.$query->USUA_USUARIO.'">
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>';
                },
                'searchable' => false,
                'orderable'  => false,
            ],
            [
                'title' => 'Nombre',
                'render' => function($query){
                    return $query->usuarioDetalle->presenter()->nombreCompleto();
                },
            ],
            [
                'title' => 'Fecha',
                'data' => 'USUA_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = '';
                    if($query->USUA_ENABLED == 1){
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

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->PUES_PUESTO.')"><i class="fa fa-eye"></i></button>';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->PUES_PUESTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/usuarios/post-data');
    }

}
