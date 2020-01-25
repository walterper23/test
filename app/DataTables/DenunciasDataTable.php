<?php

namespace App\DataTables;

use App\Model\MDocumento;

class DenunciasDataTable extends CustomDataTable
{
    public function setTableId()
    {
        return 'denuncias-datatable';
    }

    public function setSourceData()
    {
        $this->sourceData = MDocumento::join('system_tipos_documentos','DOCU_SYSTEM_TIPO_DOCTO','=','SYTD_TIPO_DOCUMENTO')
                            ->join('detalles','DOCU_DETALLE','=','DETA_DETALLE')
                            ->join('acuses_recepcion','ACUS_DOCUMENTO','=','DOCU_DOCUMENTO')
                            ->isDenuncia()->siExistente()->noGuardado()
                            ->orderBy('ACUS_NUMERO','DESC');
    }

    public function columnsTable()
    {
        return [
            [
                'title'  => 'FOLIO RECEPCIÓN',
                'data'   => 'ACUS_NUMERO',
                'width'  => '18%',
            ],
            [
                'title'  => 'ASUNTO',
                'data'   => 'DETA_DESCRIPCION',
                'width'  => '60%',
                'render' => function($documento){
                    return ellipsis($documento->DETA_DESCRIPCION,260);
                }
            ],
            [
                'title' => 'RECEPCIÓN',
                'data'  => 'DETA_FECHA_RECEPCION',
                'class' => 'text-center',
            ],
            [
                'title'  => 'OPCIONES',
                'config' => 'options', 
                'data'   => false,
                'render' => function($documento){
                    $url_editar = url( sprintf('recepcion/documentos/editar-recepcion?search=%d',$documento->getKey()) );

                    $buttons = '';

                    $buttons .= sprintf('<a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Editar recepción"><i class="fa fa-fw fa-pencil"></i></a>', $url_editar);

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.anexos(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $documento->getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->ACUS_NOMBRE) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);

                    if( user()->can('REC.ELIMINAR.LOCAL') && $documento->recepcionado() ) {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.delete_(%d)"><i class="fa fa-trash"></i></button>', $documento->getKey());
                    }

                    return $buttons;
                }
            ]
        ];
    }

    public function getUrlAjax()
    {
        return '/recepcion/documentos/post-data?type=denuncias';
    }

    public function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10,
            'order' => [[ 0, 'desc' ]]
        ];
    }

}