<?php
namespace App\DataTables;

use App\Model\MUsuario;

class UsuariosDataTable extends CustomDataTable {
    
    protected function setSourceData(){
        $this->sourceData = MUsuario::with('usuarioDetalle')->select('USUA_USUARIO','USUA_USERNAME','USUA_NOMBRE','USUA_RECENT_LOGIN','USUA_CREATED_AT','USUA_ENABLED')
                            ->where('USUA_DELETED',0);
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
                'title' => 'Usuario',
                'render' => function($query){
                    return sprintf('<a href="%s">%s</a>',url('configuracion/usuarios/ver',[$query -> USUA_USUARIO]),$query -> getAuthUsername());
                },
            ],
            [
                'title' => 'Nombre',
                'render' => function($query){
                    return $query -> UsuarioDetalle -> presenter() -> nombreCompleto();
                },
            ],
            [
                'title' => 'Descripción',
                'data' => 'USUA_NOMBRE'
            ],
            [
                'title' => 'Último acceso',
                'data' => 'USUA_RECENT_LOGIN'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = ($query -> disponible()) ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hUsuario.active({id:%d})"><span class="css-control-indicator"></span></label>',$checked,$query -> getKey());
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($query){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-warning" onclick="hUsuario.password(%d)" title="Cambiar contraseña"><i class="fa fa-key"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hUsuario.password(%d)" title="Permisos"><i class="fa fa-lock"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hUsuario.delete_(%d)"><i class="fa fa-trash"></i></button>',
                        $query -> getKey(), $query -> getKey(), $query -> getKey()
                    );
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/usuarios/post-data');
    }

}