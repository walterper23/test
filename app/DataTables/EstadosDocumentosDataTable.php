<?php
namespace App\DataTables;

use App\Model\Catalogo\MEstadoDocumento;

class EstadosDocumentosDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this -> sourceData = MEstadoDocumento::with(['Direccion','Departamento']) -> select('ESDO_ESTADO_DOCUMENTO','ESDO_DIRECCION','ESDO_DEPARTAMENTO','ESDO_NOMBRE','ESDO_CREATED_AT','ESDO_ENABLED') -> disponible() -> orderBy('ESDO_CREATED_AT','DESC') -> get();
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
                'title' => 'Dirección',
                'render' => function($query){
                    if (! is_null($query -> Direccion) )
                        return $query -> Direccion -> getNombre();
                    return '- Ninguno -';
                }
            ],
            [
                'title' => 'Departamento',
                'render' => function($query){
                    if (! is_null($query -> Departamento) )
                        return $query -> Departamento -> getNombre();
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

                    $buttons .= '<button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hEstadoDocumento.edit_('.$query->ESDO_ESTADO_DOCUMENTO.')"><i class="fa fa-pencil"></i></button>';
                
                    $buttons .= '<button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hEstadoDocumento.delete_('.$query->ESDO_ESTADO_DOCUMENTO.')"><i class="fa fa-trash"></i></button>';
                    
                    return $buttons;
                }
            ]

        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/estados-documentos/post-data');
    }
    
}
