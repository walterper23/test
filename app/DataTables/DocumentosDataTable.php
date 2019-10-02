<?php

namespace App\DataTables;

use App\Model\MDocumento;

class DocumentosDataTable extends CustomDataTable
{
    public function setTableId()
    {
        return 'documentos-datatable';
    }
    
    public function setSourceData()
    {
        $this->sourceData = MDocumento::with('TipoDocumento','Detalle','AcuseRecepcion')->isDocumentoGeneral()->siExistente()->noGuardado()->orderBy('DOCU_CREATED_AT','DESC'); // Denuncias, Documentos de denuncias
    }

    public function columnsTable()
    {
        return [
            [
                'title'  => 'FOLIO RECEPCIÓN',
                'width'  => '18%',
                'render' => function($documento){
                    return $documento->AcuseRecepcion->getNumero();
                }
            ],
            [
                'title'  => 'TIPO DOCUMENTO',
                'width'  => '12%',
                'render' => function($documento){
                    return $documento->TipoDocumento->presenter()->getBadge();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'width'  => '15%',
                'render' => function($documento){
                    return $documento->getNumero();
                }
            ],
            [
                'title'  => 'ASUNTO',
                'render' => function($documento){
                    return $documento->Detalle->presenter()->getDescripcion(260);
                }
            ],
            [
                'title' => 'Recepción',
                'class' => 'text-center',
                'render' => function($documento){
                    return $documento->Detalle->getFechaRecepcion();
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options',
                'render' => function($documento){
                    $url_editar = url( sprintf('recepcion/documentos/editar-recepcion?search=%d',$documento->getKey()) );

                    $buttons = '';

                    $buttons .= sprintf('<a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Editar recepción"><i class="fa fa-fw fa-pencil"></i></a>', $url_editar);

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.anexos(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $documento->getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->AcuseRecepcion->getNombre()) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);

                    if( user()->can('REC.ELIMINAR.LOCAL') && $documento->recepcionado() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.delete_(%d)"><i class="fa fa-trash"></i></button>', $documento->getKey());
                    }

                    return $buttons;
                }
            ]
        ];
    }

    public function getUrlAjax()
    {
        return url('recepcion/documentos/post-data?type=documentos');
    }

    public function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10,
            'order' => [[ 3, 'desc' ]]
        ];
    }

}
