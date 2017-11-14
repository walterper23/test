<?php

namespace App\DataTables;

use App\Model\Catalogo\MAnexo;

class AnexosDataTable extends CustomDataTable{
    
    protected function setSourceData(){
        $this->sourceData = MAnexo::select('ANEX_ANEXO','ANEX_NOMBRE','ANEX_ENABLED','ANEX_CREATED_AT')->where('ANEX_DELETED',0);
    }

    protected function columnsTable(){
        return [
            [
                'title' => '#',
                'render' => function($query){
                    return '<div class="custom-controls-stacked">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="'.$query->ANEX_ANEXO.'">
                                    <span class="custom-control-indicator"></span>
                                </label>
                            </div>';
                },
                'searchable' => false,
                'orderable'  => false,
            ],
            [
                'title' => 'Nombre',
                'data'  => 'ANEX_NOMBRE'
            ],
            [
                'title' => 'Fecha',
                'data'  => 'ANEX_CREATED_AT'
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" onclick="hTipoDocumento.delete('.$query->ANEX_ANEXO.')"><i class="fa fa-eye"></i></button>';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.delete('.$query->ANEX_ANEXO.')"><i class="fa fa-pencil"></i></button>';
                
                    if($query->ANEX_ENABLED == 1){
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.disable('.$query->ANEX_ANEXO.')"><i class="fa fa-level-down"></i></button>';
                    }else{
                        $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.enabled('.$query->ANEX_ANEXO.')"><i class="fa fa-level-up"></i></button>';
                    }

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->ANEX_ANEXO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/anexos/post-data');
    }

}
