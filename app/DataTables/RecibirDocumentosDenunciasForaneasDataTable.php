<?php

namespace App\DataTables;

use App\Model\MDocumento;

class RecibirDocumentosDenunciasForaneasDataTable extends CustomDataTable
{
    public function setTableId()
    {
        return 'recibir-documentos-denuncias-datatable';
    }

    public function setSourceData()
    {
        $this->sourceData = MDocumento::join('detalles','DOCU_DETALLE','=','DETA_DETALLE')
                            ->join('acuses_recepcion','DOCU_DOCUMENTO','=','ACUS_DOCUMENTO')
                            ->join('documentos_foraneos','DOCU_DOCUMENTO','=','DOFO_DOCUMENTO_LOCAL')
                            ->isDocumentoDenuncia()->isForaneo()->siExistente()->noGuardado()
                            ->orderBy('DOCU_DOCUMENTO','DESC');
    }

    public function columnsTable(){
        return [
            [
                'title'  => 'FOLIO RECEPCIÓN',
                'data'   => 'ACUS_NUMERO',
                'width'  => '18%',
            ],
            [
                'title'  => 'ASUNTO',
                'data'   => 'DETA_DESCRIPCION',
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
                'title' => 'Tránsito',
                'config' => 'badges',
                'data'   => false,
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->enviado() && !$documento->DocumentoForaneo->recibido())
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecibirRecepcionForanea.recibir(%d)" title="Recibir documento"><i class="fa fa-fw fa-folder-open"></i> Recibir</button>', $documento->DocumentoForaneo->getKey());
                    elseif( $documento->DocumentoForaneo->recibido() )
                        return $documento->DocumentoForaneo->presenter()->getBadgeRecibido();
                    else
                        return $documento->DocumentoForaneo->presenter()->getBadgeNoEnviado();
                }
            ],
            [
                'title' => 'Validado',
                'config' => 'badges',
                'data'   => false,
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->validado())
                        return $documento->DocumentoForaneo->presenter()->getBadgeValidado();
                    elseif ($documento->DocumentoForaneo->recibido())
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecibirRecepcionForanea.validar(%d)" title="Validar documento"><i class="fa fa-fw fa-check"></i> Validar</button>', $documento->DocumentoForaneo->getKey());
                    else
                        return $documento->DocumentoForaneo->presenter()->getBadgePendiente();
                }
            ],
            [
                'title' => 'Recepcionado',
                'config' => 'badges',
                'data'   => false,
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->recepcionado() )
                        return $documento->DocumentoForaneo->presenter()->getBadgeRecepcionado();
                    elseif ($documento->DocumentoForaneo->recibido())
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecibirRecepcionForanea.recepcionar(%d)" title="Recepcionar documento"><i class="fa fa-fw fa-check"></i> Recepcionar</button>', $documento->DocumentoForaneo->getKey());
                    else
                        return $documento->DocumentoForaneo->presenter()->getBadgePendiente();
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options',
                'data'   => false,
                'render' => function($documento){
                    $buttons = '';

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecibirRecepcionForanea.anexos(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $documento->getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->AcuseRecepcion->getNombre()) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);

                    return $buttons;
                }
            ]
        ];
    }

    public function getUrlAjax()
    {
        return '/recepcion/documentos/foraneos/post-data?type=documentos-denuncias';
    }

    public function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10,
            'order' => [[ 0, 'desc' ]]
        ];
    }

}
