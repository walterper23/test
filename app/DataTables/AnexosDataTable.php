<?php
namespace App\DataTables;

use App\Model\Catalogo\MAnexo;

class AnexosDataTable extends CustomDataTable{
    
    protected function setSourceData(){
        $this->sourceData = MAnexo::select('ANEX_ANEXO','ANEX_NOMBRE','ANEX_ENABLED','ANEX_CREATED_AT') -> where('ANEX_DELETED',0);
    }

    protected function columnsTable(){
        return [
            [
                'title' => '#',
                'render' => function($query){
                    return '<div class="custom-controls-stacked">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" value="'.$query->ANEX_ANEXO.'">
                                    <span class="custom-control-indicator"></span>
                                </label>
                            </div>';
                },//para no habilitar búsqueda ni orden en el elemento
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
                'title' => 'Activo',
                'render' => function($query){
                    $checked = $query -> disponible() ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hAnexo.active({id:%d})"><span class="css-control-indicator"></span></label>',$checked,$query -> getKey());
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-success" onclick="hAnexo.edit_(%d)"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hAnexo.delete_(%d)"><i class="fa fa-trash"></i></button>',
                        $query -> getKey(), $query -> getKey()
                    );
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('configuracion/catalogos/anexos/post-data');
    }

}