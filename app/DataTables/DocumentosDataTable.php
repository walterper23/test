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
        $this->sourceData = MDocumento::join('system_tipos_documentos','DOCU_SYSTEM_TIPO_DOCTO','=','SYTD_TIPO_DOCUMENTO')
                            ->join('detalles','DOCU_DETALLE','=','DETA_DETALLE')
                            ->join('acuses_recepcion','ACUS_DOCUMENTO','=','DOCU_DOCUMENTO')
                            ->isDocumentoGeneral()->siExistente()->noGuardado()->orderBy('DOCU_CREATED_AT','DESC'); // Denuncias, Documentos de denuncias
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
                'title'  => 'TIPO DOCUMENTO',
                'data'   => 'SYTD_NOMBRE',
                'width'  => '12%',
                'render' => function($documento){
                    return $documento->presenter()->getBadgeTipoDocumento();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'data'   => 'DOCU_NUMERO_DOCUMENTO',
                'width'  => '15%',
            ],
            [
                'title'  => 'ASUNTO',
                'data'   => 'DETA_DESCRIPCION',
                'render' => function($documento){
                    return ellipsis($documento->DETA_DESCRIPCION,260);
                }
            ],
            [
                'title' => 'Recepción',
                'data'  => 'DETA_FECHA_RECEPCION',
                'class' => 'text-center',
                'render' => function($documento){
                    return $documento->Detalle->getFechaRecepcion();
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options',
                'data'   => false,
                'render' => function($documento){
                    $url_editar = url( sprintf('recepcion/documentos/editar-recepcion?search=%d',$documento->getKey()) );

                    $buttons = '';

                    $buttons .= sprintf('<a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Editar recepción"><i class="fa fa-fw fa-pencil"></i></a>', $url_editar);

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.anexos(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $documento->getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->ACUS_NOMBRE) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);

                    if ( user()->can('REC.ELIMINAR.LOCAL') && $documento->recepcionado() ) {
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
