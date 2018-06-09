<?php
namespace App\DataTables;

use App\Model\Catalogo\MEstadoDocumento;

class EstadosDocumentosDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this -> sourceData = MEstadoDocumento::with('Direccion','Departamento')
                        -> select('ESDO_ESTADO_DOCUMENTO','ESDO_DIRECCION','ESDO_DEPARTAMENTO','ESDO_NOMBRE','ESDO_CREATED_AT','ESDO_ENABLED')
                        -> disponible() -> orderBy('ESDO_CREATED_AT','DESC') -> get();
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '#',
                'render' => function($estado){
                    return $estado -> getCodigo();
                }
            ],
            [
                'title' => 'Dirección',
                'render' => function($estado){
                    if (! is_null($estado -> Direccion) )
                        return $estado -> Direccion -> getNombre();
                }
            ],
            [
                'title' => 'Departamento',
                'render' => function($estado){
                    if (! is_null($estado -> Departamento) )
                        return $estado -> Departamento -> getNombre();
                    return '<p class="font-size-xs text-muted">- Ninguno -</p>';
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
                'render' => function($estado){
                    $checked = $estado -> disponible() ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hEstadoDocumento.active({id:%d})"><span class="css-control-indicator"></span></label>', $checked, $estado -> getKey());
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($estado){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hEstadoDocumento.view(%d)"><i class="fa fa-eye"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hEstadoDocumento.edit_(%d)"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hEstadoDocumento.delete_(%d)"><i class="fa fa-trash"></i></button>',
                        $estado -> getKey(), $estado -> getKey(), $estado -> getKey()
                    );
                    
                    return $buttons;
                }
            ]

        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/estados-documentos/post-data');
    }
    
}
