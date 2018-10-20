<?php
namespace App\DataTables;

use App\Model\MDocumentoForaneo;

class DocumentosForaneosDataTable extends CustomDataTable
{
    public function __construct()
    {
        parent::__construct();
        $this->builderHtml->setTableId('documentos-datatable');
    }
    
    protected function setSourceData()
    {
        $this->sourceData = MDocumentoForaneo::with('TipoDocumento','Detalle','AcuseRecepcion')->existente()->noGuardado()->whereNotIn('DOFO_SYSTEM_TIPO_DOCTO',[1,2])->get(); // Denuncias, Documentos de denuncias
    }

    protected function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'render' => function($documento){
                    return sprintf('<p class="text-center"><b>%s</b></p>',$documento->getFolio());
                }
            ],
            [
                'title'  => 'TIPO DOCUMENTO',
                'render' => function($documento){
                    return $documento->TipoDocumento->presenter()->getBadge();
                }
            ],
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
                        return '<span class="badge badge-primary">Documento enviado <i class="fa fa-fw fa-car"></i></span>';
                    elseif ($documento->recibido())
                        return '<span class="badge badge-primary">Documento recibido <i class="fa fa-fw fa-folder"></i></span>';
                    elseif (user()->can('REC.DOCUMENTO.FORANEO'))
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecepcionForanea.enviar(%d)" title="Enviar documento"><i class="fa fa-fw fa-car"></i> Enviar documento</button>', $documento->getKey());
                    else
                        return '<span class="badge badge-warning"><i class="fa fa-fw fa-car"></i> En espera</span>';
                }
            ],
            [
                'title' => 'Validado',
                'render' => function($documento){
                    if ($documento->validado() )
                        return '<span class="badge badge-success"><i class="fa fa-fw fa-check"></i> Validado</span>';
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-hourglass-start"></i> En espera</span>';
                }
            ],
            [
                'title' => 'Recepcionado',
                'render' => function($documento){
                    if ($documento->recepcionado() )
                        return '<span class="badge badge-success"><i class="fa fa-fw fa-check"></i> Recepcionado</span>';
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-hourglass-start"></i> En espera</span>';
                }
            ],

            [
                'title'  => 'Opciones',
                'render' => function($documento){
                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->AcuseRecepcion->getNombre()) );

                    //$buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hRecepcion.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $documento->getKey());
                    
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.anexos(%d)" title="Anexos del documento">
                            <i class="fa fa-fw fa-clipboard"></i>
                        </button>
                        <a class="btn btn-sm btn-circle btn-alt-success" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $documento->getKey(), $url
                    );

                    if( user()->can('REC.ELIMINAR.FORANEO') && ! $documento->recibido() )
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

}