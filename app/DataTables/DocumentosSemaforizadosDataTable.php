<?php
namespace App\DataTables;

use App\Model\MDocumentoSemaforizado;

class DocumentosSemaforizadosDataTable extends CustomDataTable
{
    protected function setSourceData()
    {
        $this -> sourceData = MDocumentoSemaforizado::with(['Documento'=>function($documento){
            $documento -> with('Detalle','Seguimientos');
        }]) -> existente() -> orderBy('DOSE_SEMAFORO','DESC') -> get();
    }

    protected function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'render' => function($semaforo){
                    return $semaforo -> getCodigo();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'render' => function($semaforo){
                    return $semaforo -> Documento -> getNumero();
                }
            ],
            [
                'title'  => 'ASUNTO',
                'render' => function($semaforo){
                    return $semaforo -> Documento -> Detalle -> getDescripcion();
                }
            ],
            [
                'title'  => 'SOLICITUD',
                'render' => function($semaforo){
                    return sprintf('%s<br><div class="font-size-sm text-muted"><i class="fa fa-calendar"></i> %s</div>',$semaforo -> DOSE_SOLICITUD,$semaforo -> DOSE_CREATED_AT);
                }
            ],
            [
                'title'  => 'SEMÁFORO',
                'render' => function($semaforo){

                    if ($semaforo -> enEspera())
                        return '<span class="badge badge-primary"><i class="fa fa-hourglass-start"></i> En espera</span>';
                    elseif ($semaforo -> respondido())
                        return '<span class="badge badge-success"><i class="fa fa-check"></i> Solicitud respondida</span>';
                    else
                        return '<span class="badge badge-danger"><i class="fa fa-times"></i> No atendido</span>';
                }
            ],
            [
                'title'  => 'RESPUESTA',
                'render' => function($semaforo){
                    return sprintf('%s<br><div class="font-size-sm text-muted"><i class="fa fa-calendar"></i> %s</div>',$semaforo -> DOSE_CONTESTACION,$semaforo -> DOSE_CONTESTACION_FECHA);
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($semaforo){
                    $buttons = '';

                    $buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hSemaforo.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $semaforo -> getKey());
                    
                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" title="Cambio de estado"><i class="fa fa-fw fa-flash"></i></button>', $semaforo -> getKey());

                    $url_seguimiento = url( sprintf('panel/documentos/seguimiento?search=%d',$semaforo -> DOSE_SEGUIMIENTO_A ) );
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