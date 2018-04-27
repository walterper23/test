<?php
namespace App\DataTables;

use App\Model\MDocumento;

class DenunciasDataTable extends CustomDataTable
{
    public function __construct()
    {
        parent::__construct();
        $this -> builderHtml -> setTableId('denuncias-datatable');
    }

    protected function setSourceData(){
        $this -> sourceData = MDocumento::with('Detalle','Denuncia','AcuseRecepcion') -> select('DOCU_DOCUMENTO','DOCU_NUMERO_DOCUMENTO','DOCU_DETALLE') -> existente() -> noGuardado() -> where('DOCU_SYSTEM_TIPO_DOCTO',1) -> get(); // Denuncia
        
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '#',
                'render' => function($documento){
                    return $documento -> getCodigo();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'data'   => 'DOCU_NUMERO_DOCUMENTO'
            ],
            [
                'title'  => 'ASUNTO',
                'render' => function($documento){
                    return $documento -> Detalle -> getDescripcion();
                }
            ],
            [
                'title' => 'RECEPCIÓN',
                'render' => function($documento){
                    return $documento -> Detalle -> getFechaRecepcion();
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($documento){
                    $buttons = '';

                    $buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hRecepcion.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $documento -> getKey());
                    
                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.anexos(%d)" title="Anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $documento -> getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento -> AcuseRecepcion -> getNombre()) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-success" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></button>', $url);

                    /*$buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-trash"></i></button>';*/
                    
                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('recepcion/documentos/post-data?type=denuncias');
    }

}