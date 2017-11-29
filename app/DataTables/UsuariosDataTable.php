<?php

namespace App\DataTables;

use App\Model\MUsuario;

class UsuariosDataTable extends CustomDataTable{
    
    protected function setSourceData(){
        $this->sourceData = MUsuario::with('usuarioDetalle')->select(['USUA_USUARIO','USUA_NOMBRE','USUA_CREATED_AT','USUA_ENABLED'])->
                            where('USUA_DELETED',0);
    }

    protected function columnsTable(){
        return [
            [
                'title' => 'Nombre',
                'render' => function($query){
                    return '<a href="'.url('configuracion/usuarios/ver',[$query->USUA_USUARIO]).'">'.$query->usuarioDetalle->presenter()->nombreCompleto().'</a>';
                },
            ],
            [
                'title' => 'Descripción',
                'data' => 'USUA_NOMBRE'
            ],
            [
                'title' => 'E-mail',
                'render' => function($query){
                    return $query->usuarioDetalle->USDE_EMAIL;
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
                                <input type="checkbox" class="css-control-input" '.$checked.' onclick="hUsuario.active({id:'.$query->USUA_USUARIO.'})"><span class="css-control-indicator"></span>
                            </label>';
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-warning" title="Cambiar contraseña" onclick="hUsuario.delete_('.$query->USUA_USUARIO.')"><i class="fa fa-key"></i></button>';


                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hUsuario.delete_('.$query->USUA_USUARIO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/usuarios/post-data');
    }

}
