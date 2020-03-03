<?php

namespace App\DataTables\imjuve;

use App\Model\imjuve\IMAfiliacion;
use App\DataTables\CustomDataTable;

class AfiliacionDataTable extends CustomDataTable
{    
    public function setSourceData()
    {
        $this->sourceData = IMAfiliacion::where('AFIL_ENABLED',1)->where('AFIL_DELETED',0)->with('Direccion','Genero');
    }

    public function columnsTable()
    {
        return [
            [
                'title' => 'NOMBRE COMPLETO',
                'config' => 'options',
                'render' => function($afil){
                    return $afil->Genero->presenter()->getIcon().' '.$afil->getNombreCompleto();
                }
            ],
            [
                'title' => 'F. N.',
                'render' => function($afil){
                    return $afil->presenter()->getFnFormat();
                }
            ],
            [
                'title' => 'Contacto',
                'render' => function($afil){
                    return $afil->presenter()->getContacto();
                }
            ],
            [
                'title' => 'Dirección',
                'render' => function($afil){
                    return $afil->Direccion->getCp().' Librado E Rivera #487, Adolfo Lopez Mateos, Chetumal.';
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options',
                'render' => function($afil){
                    $buttons = '';
                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hAfiliado.edit_(%d)" title="Editar afiliado"><i class="fa fa-fw fa-pencil"></i></button>',$afil->getKey());
                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hAfiliado.delete_(%d)" title="Eliminar afiliado"><i class="fa fa-trash"></i></button>',$afil->getKey());
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
