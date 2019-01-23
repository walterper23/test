<?php
namespace App\DataTables;

use App\Model\MDocumento;

class DenunciasForaneasDataTable extends CustomDataTable
{
    public function __construct()
    {
        parent::__construct();
        $this->builderHtml->setTableId('denuncias-datatable');
    }

    protected function setSourceData()
    {
        $this->sourceData = MDocumento::with('Detalle','DocumentoForaneo','AcuseRecepcion')->isForaneo()->siExistente()->noGuardado()->where('DOCU_SYSTEM_TIPO_DOCTO',1)->get(); // Denuncia
    }

    protected function columnsTable(){
        return [
            [
                'title'  => 'FOLIO RECEPCIÓN',
                'render' => function($denuncia){
                    return $denuncia->AcuseRecepcion->getNumero();
                }
            ],
            [
                'title'  => 'ASUNTO',
                'render' => function($query){
                    return $query->Detalle->getDescripcion();
                }
            ],
            [
                'title' => 'Recepción',
                'render' => function($query){
                    return $query->Detalle->getFechaRecepcion();
                }
            ],
            [
                'title' => 'Tránsito',
                'render' => function($denuncia){
                    if ($denuncia->DocumentoForaneo->enviado())
                        return '<span class="badge badge-primary">Enviado <i class="fa fa-fw fa-car"></i></span>';
                    elseif ($denuncia->DocumentoForaneo->recibido())
                        return '<span class="badge badge-primary">Recibido <i class="fa fa-fw fa-folder"></i></span>';
                    elseif (user()->can('REC.DOCUMENTO.FORANEO'))
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecepcionForanea.enviar(%d)" title="Enviar documento"><i class="fa fa-fw fa-car"></i> Enviar documento</button>', $denuncia->getKey());
                    else
                        return '<span class="badge badge-warning"><i class="fa fa-fw fa-car"></i> Aún no enviado</span>';
                        
                }
            ],
            [
                'title' => 'Validado',
                'render' => function($denuncia){
                    if ($denuncia->DocumentoForaneo->validado() )
                        return '<span class="badge badge-success"><i class="fa fa-fw fa-check"></i> Validado</span>';
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-times"></i> Aún no validado</span>';
                }
            ],
            [
                'title' => 'Recepcionado',
                'render' => function($denuncia){
                    if ($denuncia->DocumentoForaneo->recepcionado() )
                        return '<span class="badge badge-success"><i class="fa fa-fw fa-check"></i> ' . $denuncia->AcuseRecepcion->getNumero() . '</span>';
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-fw fa-times"></i> Aún no recepcionado</span>';
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($denuncia){

                    $url_editar = url( sprintf('recepcion/documentos-foraneos/editar-recepcion?search=%d',$denuncia->getKey()) );

                    $buttons = '';

                    //$buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hRecepcionForanea.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $denuncia->getKey());

                    $buttons .= sprintf('<a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Editar recepción"><i class="fa fa-fw fa-pencil"></i></a>', $url_editar);
                    
                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.anexos(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $denuncia->getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$denuncia->AcuseRecepcion->getNombre()) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);

                    if( user()->can('REC.ELIMINAR.FORANEO') && ! $denuncia->DocumentoForaneo->recibido() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.delete_(%d)"><i class="fa fa-trash"></i></button>', $denuncia->getKey());
                    }

                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('recepcion/documentos-foraneos/post-data?type=denuncias');
    }

    protected function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10,
            'order' => [[ 0, 'desc' ]]
        ];
    }

}