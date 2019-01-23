<?php
namespace App\DataTables;

use App\Model\MDocumento;

class RecibirDocumentosForaneosDataTable extends CustomDataTable
{
    public function __construct()
    {
        parent::__construct();
        $this->builderHtml->setTableId('recibir-documentos-datatable');
    }
    
    protected function setSourceData()
    {
        $this->sourceData = MDocumento::with('TipoDocumento','Detalle','AcuseRecepcion','DocumentoForaneo')->isForaneo()->siExistente()->noGuardado()->whereNotIn('DOCU_SYSTEM_TIPO_DOCTO',[1,2])->get(); // Denuncias, Documentos de denuncias
    }

    protected function columnsTable()
    {
        return [
            [
                'title'  => 'FOLIO RECEPCIÓN',
                'render' => function($documento){
                    return $documento->AcuseRecepcion->getNumero();
                }
            ],
            [
                'title'  => 'TIPO DOCUMENTO',
                'render' => function($documento){
                    return $documento->TipoDocumento->presenter()->getBadge();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'render' => function($documento){
                    return $documento->getNumero();
                }
            ],
            [
                'title'  => 'ASUNTO',
                'render' => function($documento){
                    return $documento->Detalle->getDescripcion();
                }
            ],
            [
                'title' => 'Recepción',
                'render' => function($documento){
                    return $documento->Detalle->getFechaRecepcion();
                }
            ],
            [
                'title' => 'Tránsito',
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
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->recepcionado() )
                        return $documento->DocumentoForaneo->presenter()->getBadgeRecepcionado();
                    elseif ($documento->DocumentoForaneo->validado())
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecibirRecepcionForanea.recepcionar(%d)" title="Recepcionar documento"><i class="fa fa-fw fa-check"></i> Recepcionar</button>', $documento->DocumentoForaneo->getKey());
                    else
                        return $documento->DocumentoForaneo->presenter()->getBadgePendiente();
                }
            ],
            [
                'title'  => 'Opciones',
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

    protected function getUrlAjax()
    {
        return url('recepcion/documentos/foraneos/post-data?type=documentos');
    }

    protected function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10,
            'order' => [[ 0, 'desc' ]]
        ];
    }

}