<?php
namespace App\DataTables;

use App\Model\Catalogo\MDireccion;

class DireccionesDataTable extends CustomDataTable{

    protected function setSourceData(){
        $this->sourceData = MDireccion::select(['DIRE_DIRECCION','DIRE_NOMBRE','DIRE_CREATED_AT','DIRE_ENABLED'])
                             -> where('DIRE_DELETED',0);
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
                'data'  => 'DIRE_NOMBRE'
            ],
            [
                'title' => 'Departamentos',
                'render' => function($query){
                    return $query -> Departamentos -> count();
                }
            ],
            [
                'title' => 'Fecha',
                'data'  => 'DIRE_CREATED_AT'
            ],
            [
                'title' => 'Activo',
                'render' => function($query){
                    $checked = $query -> disponible() ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hDireccion.active({id:%d})"><span class="css-control-indicator"></span></label>',$checked,$query -> getKey());
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hDireccion.edit_(%d)"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hDireccion.delete_(%d)"><i class="fa fa-trash"></i></button>',
                        $query -> getKey(), $query -> getKey()
                    );
                    
                    return $buttons;
                }
            ]

        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/direcciones/post-data');
    }
}