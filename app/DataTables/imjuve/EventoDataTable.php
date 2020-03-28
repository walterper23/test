<?php

namespace App\DataTables\imjuve;

use App\Model\imjuve\IMEvento;
use App\DataTables\CustomDataTable;


class EventoDataTable extends CustomDataTable
{    
    public function setSourceData()
    {
       
        $this->sourceData = IMEvento();
       
    }

    public function columnsTable()
    {
        return [

                  
            [
                'title' => 'Id',
                'width' => '20%',
                'data'  => 'EVEN_ID'
            ],

            [
                'title' => 'Nombre',
                'render' => function($evento){
                    return $evento->getNombre();
                }
            ],

            [
                'title' => 'Tipo',
                'render' => function($evento){
                    return $evento->getTipo();
                }
            ],
            [
                'title' => 'Fecha Inicio',
                'render' => function($evento){
                    return $evento->getFechaInicio();
                }
            ],
            [
                'title' => 'Fecha Fin',
                'render' => function($evento){
                    return $evento->getFechaFin();
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options',
                'render' => function($evento){

                    $buttons = '';


                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hUsuario.edit_(%d)" title="Modificar actividad"><i class="fa fa-fw fa-pencil"></i></button>',$evento->getKey());
              

                    if ( $evento->getKey() != userKey() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hUsuario.delete_(%d)" title="Eliminar"><i class="fa fa-trash"></i></button>',$evento->getKey());
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
