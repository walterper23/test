<?php
namespace App\DataTables;

use App\Model\System\MSystemTipoDocumento;

class SystemTiposDocumentosDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this->sourceData = MSystemTipoDocumento::select('SYTD_TIPO_DOCUMENTO','SYTD_NOMBRE_TIPO','SYTD_CREATED_AT','SYTD_VALIDAR','SYTD_ENABLED')
                            ->orderBy('SYTD_CREATED_AT','DESC');
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return $query -> getCodigo();
                }
            ],
            [
                'title' => 'Nombre',
                'data'  => 'SYTD_NOMBRE_TIPO'
            ],
            [
                'title' => 'Fecha',
                'data'  => 'SYTD_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = '';
                    if($query -> SYTD_ENABLED == 1){
                        $checked = 'checked=""';
                    }
                    return '<label class="css-control css-control-sm css-control-primary css-switch">
                                <input type="checkbox" class="css-control-input" '.$checked.' onclick="hTipoDocumento.active({id:'.$query -> SYTD_TIPO_DOCUMENTO.'})"><span class="css-control-indicator"></span>
                            </label>';
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hTipoDocumento.edit_('.$query -> SYTD_TIPO_DOCUMENTO.')"><i class="fa fa-pencil"></i></button>';
                
                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete_('.$query -> SYTD_TIPO_DOCUMENTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]

        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/sistema/tipos-documentos/post-data');
    }
    
}