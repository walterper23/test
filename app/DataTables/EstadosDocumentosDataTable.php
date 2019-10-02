<?php

namespace App\DataTables;

use App\Model\Catalogo\MEstadoDocumento;

class EstadosDocumentosDataTable extends CustomDataTable
{
    public function setSourceData()
    {
        $this->sourceData = MEstadoDocumento::with('Direccion','Departamento')->siExistenteDisponible();
    }

    public function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'render' => function($estado){
                    return sprintf('<b>%s</b>',$estado->getCodigo());
                }
            ],
            [
                'title'  => 'Dirección',
                'width'  => '24%',
                'render' => function($estado){
                    if (! is_null($estado->Direccion) )
                        return $estado->Direccion->getNombre();
                }
            ],
            [
                'title' => 'Departamento',
                'width'  => '24%',
                'render' => function($estado){
                    if (! is_null($estado->Departamento) )
                        return $estado->Departamento->getNombre();
                    return '<p class="font-size-xs text-muted">- Ninguno -</p>';
                }
            ],
            [
                'title' => 'Nombre',
                'width'  => '24%',
                'data'  => 'ESDO_NOMBRE'
            ],
            [
                'title' => 'Fecha',
                'class' => 'text-center',
                'data'  => 'ESDO_CREATED_AT'
            ],
            [
                'title'  => 'Activo',
                'config' => 'options', 
                'render' => function($estado){
                    $checked = $estado->disponible() ? ' checked=""' : '';
                    
                    return sprintf('<label class="css-control css-control-sm css-control-primary css-switch">
                            <input type="checkbox" class="css-control-input"%s onclick="hEstadoDocumento.active({id:%d})"><span class="css-control-indicator"></span></label>', $checked, $estado->getKey());
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options', 
                'render' => function($estado){
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hEstadoDocumento.view(%d)"><i class="fa fa-eye"></i></button>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-success" <onclic></onclic>k="hEstadoDocumento.edit_(%d)"><i class="fa fa-pencil"></i></button>',
                        $estado->getKey(), $estado->getKey()
                    );

                    if (config_var('Sistema.Estado.Recepcion.Seguimiento') != $estado->getKey()) {    
                        $buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hEstadoDocumento.delete_(%d)"><i class="fa fa-trash"></i></button>', $estado->getKey());
                    }

                    return $buttons;
                }
            ]

        ];
    }

    public function getUrlAjax()
    {
        return url('configuracion/catalogos/estados-documentos/post-data');
    }
    
}
