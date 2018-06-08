<?php
namespace App\DataTables;

use App\Model\MUsuario;

class UsuariosDataTable extends CustomDataTable {
    
    protected function setSourceData(){
        $this->sourceData = MUsuario::with('UsuarioDetalle') -> select('USUA_USUARIO','USUA_DETALLE','USUA_USERNAME','USUA_DESCRIPCION','USUA_RECENT_LOGIN','USUA_CREATED_AT','USUA_ENABLED') -> existente();
    }

    protected function columnsTable(){
        return [
            [
                'title' => '<i class="fa fa-user"></i>',
                'render' => function($usuario){
                    return user() -> presenter() -> imgAvatarSmall('img-avatar img-avatar48');
                }
            ],
            [
                'title' => '#',
                'render' => function($usuario){
                    return $usuario -> getCodigo();
                }
            ],
            [
                'title' => 'Usuario',
                'render' => function($usuario){
                    return sprintf('<span class="text-primary">%s</span>',$usuario -> getAuthUsername());
                },
            ],
            [
                'title' => 'Nombre',
                'render' => function($usuario){
                    return $usuario -> UsuarioDetalle -> presenter() -> nombreCompleto();
                },
            ],
            [
                'title' => 'Descripción',
                'data' => 'USUA_DESCRIPCION'
            ],
            [
                'title' => 'Último acceso',
                'data' => 'USUA_RECENT_LOGIN'
            ],
            [
                'title' => 'Activo',
                'render' => function($usuario){
                    $checked = ($usuario -> disponible()) ? ' checked=""' : '';
                    $enabled = $usuario -> getKey() != userKey() ? '' : ' disabled' ;

                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch%s">
                                    <input type="checkbox" class="css-control-input"%s onclick="hUsuario.active({id:%d})" %s>
                                    <span class="css-control-indicator"></span></label>', $enabled, $checked, $usuario -> getKey(), $enabled);
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($usuario){

                    $buttons = '';

                    /*$buttons = sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hUsuario.view(%d)" title="Ver usuario"><i class="fa fa-fw fa-eye"></i></button>',$usuario -> getKey());*/

                    /*$buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hUsuario.edit_(%d)" title="Modificar usuario"><i class="fa fa-fw fa-pencil"></i></button>',$usuario -> getKey());*/

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-warning" onclick="hUsuario.password(%d)" title="Cambiar contraseña"><i class="fa fa-key"></i></button>',$usuario -> getKey());

                    if (user() -> can('USU.ADMIN.PERMISOS.ASIG'))
                    {
                        $url = sprintf('configuracion/usuarios/permisos-asignaciones?user=%d', $usuario -> getKey());
                        $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Permisos y Asignaciones"><i class="fa fa-lock"></i></a>',url($url));
                    }

                    if ( $usuario -> getKey() != userKey() )
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hUsuario.delete_(%d)" title="Eliminar"><i class="fa fa-trash"></i></button>',$usuario -> getKey());
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/usuarios/post-data');
    }

}