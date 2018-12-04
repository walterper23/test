<?php
namespace App\DataTables;

use App\Model\MDocumentoForaneo;

class RecibirDenunciasForaneasDataTable extends CustomDataTable
{
    public function __construct()
    {
        parent::__construct();
        $this->builderHtml->setTableId('recibir-denuncias-datatable');
    }

    protected function setSourceData(){
        $this->sourceData = MDocumentoForaneo::with(['Detalle','Documento'=>function($query){
            $query->with('AcuseRecepcion');
        }])->existente()->noGuardado()->where('DOFO_SYSTEM_TIPO_DOCTO',1)->orderBy('DOFO_DOCUMENTO','DESC')->get(); // Denuncia
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
                    if ($documento->enviado())
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecibirRecepcionForanea.recibir(%d)" title="Recibir documento"><i class="fa fa-fw fa-folder-open"></i> Recibir</button>', $documento->getKey());
                    elseif( $documento->recibido() )
                        return '<span class="badge badge-primary"><i class="fa fa-fw fa-folder"></i> Documento recibido</span>';
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-car"></i> Aún no enviado</span>';
                }
            ],
            [
                'title' => 'Validado',
                'render' => function($documento){
                    if ($documento->validado())
                        return '<span class="badge badge-success"><i class="fa fa-fw fa-check"></i> Validado</span>';
                    elseif ($documento->recibido())
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecibirRecepcionForanea.validar(%d)" title="Validar documento"><i class="fa fa-fw fa-check"></i> Validar</button>', $documento->getKey());
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-times"></i> Aún sin validar</span>';
                }
            ],
            [
                'title' => 'Recepcionado',
                'render' => function($documento){
                    if ($documento->recepcionado() )
                        return '<span class="badge badge-success"><i class="fa fa-fw fa-check"></i> ' . $documento->Documento->AcuseRecepcion->getNumero() . '</span>';
                    elseif ($documento->recibido())
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecibirRecepcionForanea.recepcionar(%d)" title="Recepcionar documento"><i class="fa fa-fw fa-check"></i> Recepcionar</button>', $documento->getKey());
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-times"></i> Aún sin validar</span>';
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($documento){
                    $buttons = '';

                    //$buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hRecepcion.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $documento->getKey());
                    
                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecibirRecepcionForanea.anexos(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $documento->getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->AcuseRecepcion->getNombre()) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('recepcion/documentos/foraneos/post-data?type=denuncias');
    }

    protected function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10
        ];
    }

}