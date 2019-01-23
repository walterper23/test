<?php
namespace App\DataTables;

use App\Model\MDocumento;

class DocumentosForaneosDataTable extends CustomDataTable
{
    public function __construct()
    {
        parent::__construct();
        $this->builderHtml->setTableId('documentos-datatable');
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
                        return $documento->DocumentoForaneo->presenter()->getBadgeEnviado();
                    elseif ($documento->DocumentoForaneo->recibido())
                        return $documento->DocumentoForaneo->presenter()->getBadgeRecibido();
                    elseif (user()->can('REC.DOCUMENTO.FORANEO'))
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecepcionForanea.enviar(%d)" title="Enviar documento"><i class="fa fa-fw fa-car"></i> Enviar documento</button>', $documento->DocumentoForaneo->getKey());
                }
            ],
            [
                'title' => 'Validado',
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->validado())
                        return $documento->DocumentoForaneo->presenter()->getBadgeValidado();
                    else
                        return $documento->DocumentoForaneo->presenter()->getBadgeEnEspera();
                }
            ],
            [
                'title' => 'Recepcionado',
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->recepcionado() )
                        return $documento->DocumentoForaneo->presenter()->getBadgeRecepcionado();
                    else
                        return $documento->DocumentoForaneo->presenter()->getBadgeEnEspera();
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($documento){
                    $url_editar = url( sprintf('recepcion/documentos-foraneos/editar-recepcion?search=%d',$documento->DocumentoForaneo->getKey()) );

                    $buttons = '';

                    $buttons .= sprintf('<a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Editar recepción"><i class="fa fa-fw fa-pencil"></i></a>', $url_editar);

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.anexos(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $documento->getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->AcuseRecepcion->getNombre()) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);

                    if( user()->can('REC.ELIMINAR.FORANEO') && $documento->recepcionado() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.delete_(%d)"><i class="fa fa-trash"></i></button>', $documento->DocumentoForaneo->getKey());
                    }

                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax()
    {
        return url('recepcion/documentos-foraneos/post-data?type=documentos');
    }

    protected function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10,
            'order' => [[ 0, 'desc' ]]
        ];
    }

}