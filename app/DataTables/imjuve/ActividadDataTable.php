<?php

namespace App\DataTables\imjuve;

use App\Model\imjuve\IMActividad;
use App\Model\imjuve\IMTipoActividad;
use App\DataTables\CustomDataTable;



class ActividadDataTable extends CustomDataTable
{    
    public function setSourceData()
    {
       
        $this->sourceData = IMActividad::with('tipoAct');
       
    }

    public function columnsTable()
    {
        return [

                  
            [
                'title' => 'Id',
                'width' => '20%',
                'data'  => 'ACTI_ID'
            ],

            [
                'title' => 'Tipo de actividad',
                'render' => function($actividad){
                    return $actividad->tipoAct->getNombre();
                }
            ],

            [
                'title' => 'Nombre',
                'render' => function($actividad){
                    return $actividad->getNombre();
                }
            ],
            [
                'title' => 'Descripcion',
                'render' => function($actividad){
                    return $actividad->getDescripcion();
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options',
                'render' => function($actividad){

                    $buttons = '';


                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hUsuario.edit_(%d)" title="Modificar actividad"><i class="fa fa-fw fa-pencil"></i></button>',$actividad->getKey());
              

                    if ( $actividad->getKey() != userKey() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hUsuario.delete_(%d)" title="Eliminar"><i class="fa fa-trash"></i></button>',$actividad->getKey());
                    }
                    
                    return $buttons;
                }
            ]


            
            
        ];
    }

    public function getUrlAjax()
    {
        return url('imjuve/actividades/post-data');
    }

    public function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 50,
            'order' => [[ 1, 'asc' ]]
        ];
    }

}
