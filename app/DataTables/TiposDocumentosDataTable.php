<?php

namespace App\DataTables;

use App\Model\Catalogo\MTipoDocumento;

class TiposDocumentosDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this->sourceData = MTipoDocumento::selectRaw('TIDO_TIPO_DOCUMENTO, TIDO_NOMBRE_TIPO, TIDO_CREATED_AT, TIDO_VALIDAR, TIDO_ENABLED')
                            ->where('TIDO_DELETED',0)->orderBy('TIDO_CREATED_AT','DESC')->get();
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return '<div class="custom-controls-stacked">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->TIDO_TIPO_DOCUMENTO.'">
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>';
                },
                'searchable' => false,
                'orderable'  => false,
            ],
            [
                'title' => 'Nombre',
                'data'  => 'TIDO_NOMBRE_TIPO'
            ],
            [
                'title' => 'Fecha',
                'data'  => 'TIDO_CREATED_AT'
            ],
            [
                'title' => 'Validar',
                'render' => function($query){
                    if($query->TIDO_VALIDAR == 1){
                        return '<span class="badge badge-success">Validar</span>';
                    }
                    return '<span class="badge badge-info">No validar</span>';
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.edit('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-pencil"></i></button>';
                
                    if($query->TIDO_ENABLED == 1){
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-level-down"></i></button>';
                    }else{
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.disable('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-level-up"></i></button>';
                    }

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->TIDO_TIPO_DOCUMENTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]

        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/tipos-documentos/post-data');
    }
    
}
