<?php
namespace App\DataTables;

use App\Model\MDocumentoSemaforizado;

class DocumentosSemaforizadosDataTable extends CustomDataTable
{
    protected function setSourceData()
    {
        $this->sourceData = MDocumentoSemaforizado::with(['Documento'=>function($documento){
            $documento->with('Detalle','AcuseRecepcion');
        },'SeguimientoA','SeguimientoB'])->existente()->orderBy('DOSE_SEMAFORO','DESC')->get();
    }

    protected function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'render' => function($semaforo){
                    return $semaforo->getCodigo();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'render' => function($semaforo){
                    return $semaforo->Documento->getNumero();
                }
            ],
            [
                'title'  => 'ASUNTO',
                'render' => function($semaforo){
                    return $semaforo->Documento->Detalle->getDescripcion();
                }
            ],
            [
                'title'  => 'SOLICITUD',
                'render' => function($semaforo){
                    $seguimiento = sprintf('<a class="text-danger" href="#" onclick="hSemaforo.verSeguimiento(1,%d)"><i class="fa fa-fw fa-flash"></i> <b>#%s</b></a>',
                        $semaforo->getKey(), $semaforo->SeguimientoA->getCodigo() );

                    return sprintf('%s<br><div class="font-size-xs text-muted">
                                    <i class="fa fa-calendar"></i> %s %s</div>',$semaforo->getSolicitud(),$semaforo->DOSE_CREATED_AT, $seguimiento);
                }
            ],
            [
                'title'  => 'SEMÁFORO',
                'render' => function($semaforo){

                    if ($semaforo->enEspera())
                        return '<span class="badge badge-primary"><i class="fa fa-hourglass-start"></i> En espera</span>';
                    elseif ($semaforo->respondido())
                        return '<span class="badge badge-success"><i class="fa fa-check"></i> Solicitud respondida</span>';
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-times"></i> No atendido</span>';
                }
            ],
            [
                'title'  => 'RESPUESTA',
                'render' => function($semaforo){
                    if ($semaforo->respondido())
                    {
                        $seguimiento = sprintf('<a class="text-danger" href="#" onclick="hSemaforo.verSeguimiento(2,%d)"><i class="fa fa-fw fa-flash"></i> <b>#%s</b></a>',
                        $semaforo->getKey(), $semaforo->SeguimientoB->getCodigo() );

                        return sprintf('%s<br><div class="font-size-xs text-muted">
                                    <i class="fa fa-calendar"></i> %s %s</div>',$semaforo->getRespuesta(),$semaforo->getRespuestaFecha(), $seguimiento);
                    }
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($semaforo){
                    $buttons = '';

                    $url = url( sprintf('recepcion/acuse/documento/%s',$semaforo->Documento->AcuseRecepcion->getNombre()) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-success font-size-xs" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);

                    $url_seguimiento = url( sprintf('panel/documentos/seguimiento?search=%d',$semaforo->DOSE_SEGUIMIENTO_A ) );
                    $buttons .= sprintf(' <a href="%s" class="btn btn-sm btn-circle btn-alt-success" title="Ver seguimiento"><i class="fa fa-fw fa-paper-plane"></i></a>', $url_seguimiento);

                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax()
    {
        return url('panel/documentos/semaforizados/post-data');
    }

}