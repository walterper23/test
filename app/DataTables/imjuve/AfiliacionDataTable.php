<?php

namespace App\DataTables\imjuve;

use App\Model\imjuve\IMAfiliacion;
use App\DataTables\CustomDataTable;

class AfiliacionDataTable extends CustomDataTable
{    
    public function setSourceData()
    {
        $this->sourceData = IMAfiliacion::with('Direccion','Genero');
    }

    public function columnsTable()
    {
        return [
            [
                'title' => 'NOMBRES',
                'config' => 'options',
                'render' => function($afil){
                    return $afil->getNombres();
                }
            ],
            [
                'title' => 'C.P',
                'render' => function($afil){
                    return $afil->Direccion->getCp();
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options',
                'render' => function($usuario){

                    $buttons = '';/*

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hUsuario.edit_(%d)" title="Modificar usuario"><i class="fa fa-fw fa-pencil"></i></button>',$usuario->getKey());

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-warning" onclick="hUsuario.password(%d)" title="Cambiar contraseña"><i class="fa fa-key"></i></button>',$usuario->getKey());

                    if (user()->can('USU.ADMIN.PERMISOS.ASIG'))
                    {
                        $url = sprintf('configuracion/usuarios/permisos-asignaciones?user=%d', $usuario->getKey());
                        $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Permisos y Asignaciones"><i class="fa fa-lock"></i></a>',url($url));
                    }

                    if ( $usuario->getKey() != userKey() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hUsuario.delete_(%d)" title="Eliminar"><i class="fa fa-trash"></i></button>',$usuario->getKey());
                    }*/
                    
                    return $buttons;
                }
            ]
        ];
    }

    public function getUrlAjax()
    {
        return url('imjuve/afiliacion/post-data');
    }

    public function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 50,
            'order' => [[ 1, 'asc' ]]
        ];
    }

}
