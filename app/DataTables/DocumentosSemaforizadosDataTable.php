<?php

namespace App\DataTables;

use App\Model\MDocumentoSemaforizado;

class DocumentosSemaforizadosDataTable extends CustomDataTable
{
    public function setSourceData()
    {
        $this->sourceData = MDocumentoSemaforizado::with(['Documento.Detalle','Documento.AcuseRecepcion','SeguimientoA','SeguimientoB.DireccionDestino','SeguimientoB.DepartamentoDestino'])->existente()->orderBy('DOSE_SEMAFORO','DESC');
    }

    public function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'class'  => 'text-center',
                'width'  => '5%',
                'render' => function($semaforo){
                    return $semaforo->getCodigo();
                }
            ],
            [
                'title'  => 'DETALLES DOCUMENTO',
                'width'  => '15%',
                'render' => function($semaforo){
                    return sprintf('<b>%s</b><br>%s',$semaforo->Documento->getNumero(),$semaforo->Documento->Detalle->getDescripcion());
                }
            ],
            [
                'title'  => 'ÁREA TURNADA',
                'width'  => '31%',
                'render' => function($semaforo){
                    $departamento = is_null($semaforo->SeguimientoA->DepartamentoDestino) ? '' : $semaforo->SeguimientoA->DepartamentoDestino->getNombre();

                    return sprintf('<b>%s</b><br>%s',$semaforo->SeguimientoA->DireccionDestino->getNombre(),$departamento);
                }
            ],
            [
                'title'  => 'SOLICITUD',
                'width'  => '22%',
                'render' => function($semaforo){
                    $seguimiento = sprintf('<a class="text-danger" href="#" onclick="hSemaforo.verSeguimiento(1,%d)"><i class="fa fa-fw fa-flash"></i> <b>#%s</b></a>',
                        $semaforo->getKey(), $semaforo->SeguimientoA->getCodigo() );

                    return sprintf('%s<br><div class="font-size-xs text-muted">
                                    <i class="fa fa-calendar"></i> %s<br>%s</div>',$semaforo->getSolicitud(),$semaforo->DOSE_CREATED_AT, $seguimiento);
                }
            ],
            [
                'title'  => 'RESPUESTA',
                'width'  => '22%',
                'render' => function($semaforo){
                    if ($semaforo->enEspera()) {
                        return '<span class="badge badge-primary"><i class="fa fa-hourglass-start"></i> En espera</span>';
                    } else if ($semaforo->respondido()) {
                        $seguimiento = sprintf('<a class="text-danger" href="#" onclick="hSemaforo.verSeguimiento(2,%d)"><i class="fa fa-fw fa-flash"></i> <b>#%s</b></a>',
                        $semaforo->getKey(), $semaforo->SeguimientoB->getCodigo() );

                        return sprintf('%s<br><div class="font-size-xs text-muted">
                                    <i class="fa fa-calendar"></i> %s<br>%s</div>',$semaforo->getRespuesta(),$semaforo->getRespuestaFecha(), $seguimiento);
                    } else {
                        return '<span class="badge badge-danger"><i class="fa fa-times"></i> No atendido</span>';
                    }
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options',
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

    public function getUrlAjax()
    {
        return url('panel/documentos/semaforizados/post-data');
    }

}
