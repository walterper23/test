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
        $this->sourceData = MDocumento::with('TipoDocumento','Detalle','AcuseRecepcion','DocumentoForaneo')->wForaneo()->siExistente()->noGuardado()->whereNotIn('DOCU_SYSTEM_TIPO_DOCTO',[1,2])->get(); // Denuncias, Documentos de denuncias
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
                    if ($documento->DocumentoForaneo->enviado())
                        return sprintf('<span class="badge badge-primary" title="%s">Enviado <i class="fa fa-fw fa-car"></i></span>',$documento->DocumentoForaneo->getFechaEnviado());
                    elseif ($documento->DocumentoForaneo->recibido())
                        return '<span class="badge badge-primary">Recibido <i class="fa fa-fw fa-folder"></i></span>';
                    elseif (user()->can('REC.DOCUMENTO.FORANEO'))
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecepcionForanea.enviar(%d)" title="Enviar documento"><i class="fa fa-fw fa-car"></i> Enviar documento</button>', $documento->DocumentoForaneo->getKey());
                    else
                        return '<span class="badge badge-warning"><i class="fa fa-fw fa-car"></i> En espera</span>';
                }
            ],
            [
                'title' => 'Validado',
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->validado() )
                        return sprintf('<span class="badge badge-success" title="%s"><i class="fa fa-fw fa-check"></i> Validado</span>',$documento->DocumentoForaneo->getFechaValidado());
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-hourglass-start"></i> En espera</span>';
                }
            ],
            [
                'title' => 'Recepcionado',
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->recepcionado() )
                        return sprintf('<span class="badge badge-success" title="%s"><i class="fa fa-fw fa-check"></i> Recepcionado</span>',$documento->DocumentoForaneo->getFechaRecepcionado());
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-hourglass-start"></i> En espera</span>';
                }
            ],

            [
                'title'  => 'Opciones',
                'render' => function($documento){
                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->AcuseRecepcion->getNombre()) );

                    $url_editar = url( sprintf('recepcion/documentos-foraneos/editar-recepcion?search=%d',$documento->getKey()) );

                    //$buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hRecepcion.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $documento->getKey());
                    
                    $buttons = sprintf('
                        <a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Editar recepción"><i class="fa fa-fw fa-pencil"></i></a>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.anexos(%d)" title="Anexos del documento">
                            <i class="fa fa-fw fa-clipboard"></i>
                        </button>
                        <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>',
                        $url_editar, $documento->getKey(), $url
                    );

                    if( user()->can('REC.ELIMINAR.FORANEO') && ! $documento->DocumentoForaneo->recibido() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.delete_(%d)"><i class="fa fa-trash"></i></button>', $documento->getKey());
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