<?php

namespace App\DataTables\imjuve;

use App\Model\imjuve\IMInstituto;
use App\DataTables\CustomDataTable;

class InstitutoDataTable extends CustomDataTable
{    
    public function setSourceData()
    {
        $this->sourceData = IMInstituto::where('ORGA_ENABLED',1)->with('Direccion');

  
    }
    

    public function columnsTable()
    {
       
        return [
          
            [
                'title'  => 'Institucion',
                'width'  => '20%',
                'render' => function($instituto){
                    return sprintf('<span class="text-primary">%s</span>',$instituto->getAlias());
                },
            ],
            [
                'title'  => 'Razon social',
                'width'  => '20%',
                'render' => function($instituto){
                    return sprintf('<span class="text-primary">%s</span>',$instituto->getRazonSocial());
                },
            ],
            [
                'title' => 'Numero',
                'width'  => '20%',
                'render' => function($instituto){
                    return sprintf('<span class="text-primary">%s</span>',$instituto->getTelefono());
                },
            ],
            [
                'title' => 'Calle(s)',
                'width'  => '20%',
                'render' => function($instituto){
                    return  sprintf('<span class="text-primary">%s</span>',$instituto->Direccion->getDireccionCompleta());
                }
            ],
         
       
         
            [
                'title'  => 'Opciones',
                'config' => 'options',
                'render' => function($instituto){

                    $buttons = '';


                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hInstituto.edit_(%d)" title="Modificar Instituto"><i class="fa fa-fw fa-pencil"></i></button>',$instituto->getKey());

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hInstituto.delete_(%d)" title="Eliminar Instituto"><i class="fa fa-trash"></i></button>',$instituto->getKey());
                    
                    
                    return $buttons;
                }
            ]
           
          
        
         
        ];
    }

    public function getUrlAjax()
    {
       
        return url('imjuve/instituto/post-data');
    }

    public function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 50,
            'order' => [[ 1, 'asc' ]]
        ];
    }

}
