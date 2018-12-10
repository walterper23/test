<?php
namespace App\DataTables;

use App\Model\MDocumento;

class DenunciasDataTable extends CustomDataTable
{
    public function __construct()
    {
        parent::__construct();
        $this->builderHtml->setTableId('denuncias-datatable');
    }

    protected function setSourceData(){
        $this->sourceData = MDocumento::with('Detalle','Denuncia','AcuseRecepcion')->existente()->noGuardado()->where('DOCU_SYSTEM_TIPO_DOCTO',1)->orderBy('DOCU_DOCUMENTO','DESC')->get(); // Denuncia
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
                'title'  => 'ASUNTO',
                'render' => function($documento){
                    return $documento->Detalle->getDescripcion();
                }
            ],
            [
                'title' => 'RECEPCIÓN',
                'render' => function($documento){
                    return $documento->Detalle->getFechaRecepcion();
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($documento){

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->AcuseRecepcion->getNombre()) );

                    $url_editar = url( sprintf('recepcion/documentos/editar-recepcion?search=%d',$documento->getKey()) );

                    //$buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hRecepcion.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $documento->getKey());
                    
                    $buttons = sprintf('
                        <a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Editar recepción"><i class="fa fa-fw fa-pencil"></i></a>
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.anexos(%d)" title="Anexos del documento">
                            <i class="fa fa-fw fa-clipboard"></i>
                        </button>
                        <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>',
                        $url_editar, $documento->getKey(), $url
                    );

                    if( user()->can('REC.ELIMINAR.LOCAL') && $documento->recepcionado() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.delete_(%d)"><i class="fa fa-trash"></i></button>', $documento->getKey());
                    }

                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('recepcion/documentos/post-data?type=denuncias');
    }

    protected function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10
        ];
    }

}