<?php

namespace App\DataTables;

use App\Model\MDocumento;

class DocumentosDataTable extends CustomDataTable{
    
    protected function setSourceData(){
        $this->sourceData = MDocumento::select('DOCU_DOCUMENTO','DOCU_NUMERO_OFICIO','DOCU_ANIO','DOCU_DESCRIPCION','DOCU_ENABLED','DOCU_CREATED_AT')
                            ->where('DOCU_DELETED',0);
    }

    protected function columnsTable(){
        return [
            [
                'title' => '# OFICIO',
                'data'  => 'DOCU_NUMERO_OFICIO'
            ],
            [
                'title' => 'Año',
                'data'  => 'DOCU_ANIO'
            ],
            [
                'title' => 'Descripción',
                'data'  => 'DOCU_DESCRIPCION'
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-eye"></i></button>';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-pencil"></i></button>';
                
                    if($query->ANEX_ENABLED == 1){
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-level-down"></i></button>';
                    }else{
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.enabled('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-level-up"></i></button>';
                    }

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('recepcion/documentos/post-data');
    }

}
