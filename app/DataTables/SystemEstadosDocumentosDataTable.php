<?php
namespace App\DataTables;

use App\Model\Catalogo\MEstadoDocumento;

class SystemEstadosDocumentosDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this->sourceData = MEstadoDocumento::with(['direccion','departamento'])->selectRaw('ESDO_ESTADO_DOCUMENTO, ESDO_DIRECCION, ESDO_DEPARTAMENTO, ESDO_NOMBRE, ESDO_CREATED_AT, ESDO_ENABLED')->where('ESDO_DELETED',0)->orderBy('ESDO_CREATED_AT','DESC')->get();
    }

    protected function columnsTable(){
        return [
            /*[
                'title'  => '#',
                'render' => function($query){
                    return '<div class="custom-controls-stacked">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="'.$query->ESDO_ESTADO_DOCUMENTO.'">
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>';
                },
                'searchable' => false,
                'orderable'  => false,
            ],*/
            [
                'title' => 'Dirección',
                'render' => function($query){
                    if( $query->direccion != null ){
                        return $query->direccion->presenter()->link();
                    }
                    return '- Ninguno -';
                }
            ],
            [
                'title' => 'Departamento',
                'render' => function($query){
                    if( $query->departamento != null ){
                        return $query->departamento->presenter()->link();
                    }
                    return '- Ninguno -';
                }
            ],
            [
                'title' => 'Nombre',
                'data'  => 'ESDO_NOMBRE'
            ],
            [
                'title' => 'Fecha',
                'data'  => 'ESDO_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = '';
                    if($query->ESDO_ENABLED == 1){
                        $checked = 'checked=""';
                    }
                    return '<label class="css-control css-control-sm css-control-primary css-switch">
                                <input type="checkbox" class="css-control-input" '.$checked.' onclick="hEstadoDocumento.active({id:'.$query->ESDO_ESTADO_DOCUMENTO.'})"><span class="css-control-indicator"></span>
                            </label>';
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hEstadoDocumento.edit_('.$query->ESDO_ESTADO_DOCUMENTO.')"><i class="fa fa-pencil"></i></button>';
                
                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hEstadoDocumento.delete_('.$query->ESDO_ESTADO_DOCUMENTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]

        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/estados-documentos/post-data');
    }
    
}
