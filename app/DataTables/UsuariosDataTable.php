<?php
namespace App\DataTables;

use App\Model\MUsuario;

class UsuariosDataTable extends CustomDataTable {
    
    protected function setSourceData(){
        $this->sourceData = MUsuario::with('UsuarioDetalle') -> select('USUA_USUARIO','USUA_DETALLE','USUA_USERNAME','USUA_NOMBRE','USUA_RECENT_LOGIN','USUA_CREATED_AT','USUA_ENABLED') -> existente();
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
                    return sprintf('<span class="text-primary">%s</span>',$query -> getAuthUsername());
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
                    $enabled = $query -> getKey() != userKey() ? '' : ' disabled' ;

                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch%s">
                                    <input type="checkbox" class="css-control-input"%s onclick="hUsuario.active({id:%d})" %s>
                                    <span class="css-control-indicator"></span></label>', $enabled, $checked, $query -> getKey(), $enabled);
                }
            ],
            [
                'title' => 'Opciones',
                'render' => function($query){

                    $buttons = sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hUsuario.view(%d)" title="Ver usuario"><i class="fa fa-fw fa-eye"></i></button>',$query -> getKey());

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hUsuario.edit_(%d)" title="Modificar usuario"><i class="fa fa-fw fa-pencil"></i></button>',$query -> getKey());

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-warning" onclick="hUsuario.password(%d)" title="Cambiar contraseña"><i class="fa fa-key"></i></button>',$query -> getKey());

                    if (user() -> can('USU.ADMIN.PERMISOS.ASIG'))
                    {
                        $url = sprintf('configuracion/usuarios/permisos-asignaciones?user=%d', $query -> getKey());
                        $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Permisos y Asignaciones"><i class="fa fa-lock"></i></a>',url($url));
                    }

                    if ( $query -> getKey() != userKey() )
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hUsuario.delete_(%d)" title="Eliminar"><i class="fa fa-trash"></i></button>',$query -> getKey());
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/usuarios/post-data');
    }

}