<?php

namespace App\DataTables;

use App\Model\Acl\MRol;

class RolesDataTable extends CustomDataTable{
    
    protected function setSourceData(){
        $this->sourceData = MRol::select(['ROLE_ROL','ROLE_NOMBRE','ROLE_CREATED_AT','ROLE_ENABLED'])
                            ->where('ROLE_DELETED',0);
    }

    protected function columnsTable(){
        return [
            [
                'title' => 'Nombre',
                'data' => 'ROLE_NOMBRE'
            ],
            [
                'title' => 'Permisos',
                'render' => function($query){
                    return '<a href="#">Ver permisos</a>';
                }
            ],
            [
                'title' => 'Fecha',
                'data' => 'ROLE_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = '';
                    if($query->ROLE_ENABLED == 1){
                        $checked = 'checked=""';
                    }
                    return '<label class="css-control css-control-sm css-control-primary css-switch">
                                <input type="checkbox" class="css-control-input" '.$checked.' onclick="hRol.active({id:'.$query->ROLE_USUARIO.'})"><span class="css-control-indicator"></span>
                            </label>';
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->ROLE_PUESTO.')"><i class="fa fa-pencil"></i></button>';
                
                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hRol.delete_('.$query->ROLE_PUESTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/usuarios/roles/post-data');
    }

}
