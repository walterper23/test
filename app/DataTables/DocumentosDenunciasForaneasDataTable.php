<?php
namespace App\DataTables;

use App\Model\MDocumentoForaneo;

class DocumentosDenunciasForaneasDataTable extends CustomDataTable
{
    public function __construct()
    {
        parent::__construct();
        $this->builderHtml->setTableId('documentos-denuncias-datatable');
    }

    protected function setSourceData()
    {
        $this->sourceData = MDocumentoForaneo::with(['Detalle','Documento'=>function($query){
            $query->with('AcuseRecepcion');
        }])->where('DOFO_SYSTEM_TIPO_DOCTO',2)->existente()->noGuardado()->get(); // Documentos de denuncias
    }

    protected function columnsTable(){
        return [
            // [
            //     'title'  => '#',
            //     'render' => function($documento){
            //         return sprintf('<p class="text-center"><b>%s</b></p>',$documento->getFolio());
            //     }
            // ],
            [
                'title'  => 'FOLIO RECEPCIÓN',
                'render' => function($documento){
                    return $documento->AcuseRecepcion->getNumero();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'data'   => 'DOFO_NUMERO_DOCUMENTO'
            ],
            [
                'title'  => 'ASUNTO',
                'render' => function($query){
                    return $query->Detalle->getDescripcion();
                }
            ],
            [
                'title' => 'RECEPCIÓN',
                'render' => function($query){
                    return $query->Detalle->getFechaRecepcion();
                }
            ],
            [
                'title' => 'Tránsito',
                'render' => function($documento){
                    if ($documento->enviado())
                        return '<span class="badge badge-primary">Enviado <i class="fa fa-fw fa-car"></i></span>';
                    elseif ($documento->recibido())
                        return '<span class="badge badge-primary">Recibido <i class="fa fa-fw fa-folder"></i></span>';
                    elseif (user()->can('REC.DOCUMENTO.FORANEO'))
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecepcionForanea.enviar(%d)" title="Enviar documento"><i class="fa fa-fw fa-car"></i> Enviar documento</button>', $documento->getKey());
                    else
                        return '<span class="badge badge-warning"><i class="fa fa-fw fa-car"></i> Aún no enviado</span>';
                }
            ],
            [
                'title' => 'Validado',
                'render' => function($documento){
                    if ($documento->validado() )
                        return '<span class="badge badge-success"><i class="fa fa-fw fa-check"></i> Validado</span>';
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-times"></i> Aún no validado</span>';
                }
            ],
            [
                'title' => 'Recepcionado',
                'render' => function($documento){
                    if ($documento->recepcionado() )
                        return '<span class="badge badge-success"><i class="fa fa-fw fa-check"></i> ' . $documento->Documento->AcuseRecepcion->getNumero() . '</span>';
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-times"></i> Aún no recepcionado</span>';
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($documento){
                    $url_editar = url( sprintf('recepcion/documentos-foraneos/editar-recepcion?search=%d',$documento->getKey()) );

                    $buttons = '';

                    //$buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hRecepcion.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $documento->getKey());

                    $buttons .= sprintf('<a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Editar recepción"><i class="fa fa-fw fa-pencil"></i></a>', $url_editar);
                    
                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.anexos(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $documento->getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->AcuseRecepcion->getNombre()) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);

                    if( user()->can('REC.ELIMINAR.FORANEO') && ! $documento->recibido() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.delete_(%d)"><i class="fa fa-trash"></i></button>', $documento->getKey());
                    }
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('recepcion/documentos-foraneos/post-data?type=documentos-denuncias');
    }

    protected function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10,
            'order' => [[ 0, 'desc' ]]
        ];
    }

}