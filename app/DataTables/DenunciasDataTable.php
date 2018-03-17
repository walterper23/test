<?php
namespace App\DataTables;

use App\Model\MDocumento;

class DenunciasDataTable extends CustomDataTable {
    
    protected function setSourceData(){
        $this -> sourceData = MDocumento::select('DOCU_DOCUMENTO','DOCU_NUMERO_FICHA','DOCU_NUMERO_OFICIO','DOCU_FECHA_RECEPCION','DOCU_ANIO','DOCU_DESCRIPCION','DOCU_ENABLED','DOCU_CREATED_AT')->orderBy('DOCU_NUMERO_OFICIO','DESC')
                            ->where('DOCU_DELETED',0);
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '# FICHA',
                'render' => function($query){
                    return "<a href='".url('recepcion/documentos', $query->DOCU_DOCUMENTO)."'>
                                <i class='fa fa-file'></i> {$query->DOCU_NUMERO_FICHA}
                            </a>";
                }
            ],
            [
                'title'  => 'OFICIO',
                'render' => function($query){
                    return "<a href='".url('recepcion/documentos', $query->DOCU_DOCUMENTO)."'>
                                {$query->DOCU_NUMERO_OFICIO}
                            </a>";
                }
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
                'title' => 'Recepción',
                'data'  => 'DOCU_FECHA_RECEPCION'
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<a href="'.url('recepcion/documentos/'.$query->DOCU_DOCUMENTO.'/seguimiento').'" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" ><i class="fa fa-eye"></i></a>';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-file-pdf-o"></i></button>';

                    /*$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-trash"></i></button>';*/
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('recepcion/documentos/post-data');
    }

}