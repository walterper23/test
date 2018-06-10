<?php
namespace App\DataTables;

use App\Model\MDocumento;

class DocumentosDataTable extends CustomDataTable
{
    public function __construct()
    {
        parent::__construct();
        $this -> builderHtml -> setTableId('documentos-datatable');
    }
    
    protected function setSourceData()
    {
        $this -> sourceData = MDocumento::with('TipoDocumento','Detalle','AcuseRecepcion') -> select('DOCU_DOCUMENTO','DOCU_NUMERO_DOCUMENTO','DOCU_DETALLE','DOCU_SYSTEM_TIPO_DOCTO','DOCU_SYSTEM_ESTADO_DOCTO') -> existente() -> noGuardado() -> whereNotIn('DOCU_SYSTEM_TIPO_DOCTO',[1,2]) -> get(); // Denuncias, Documentos de denuncias
    }

    protected function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'render' => function($documento){
                    return $documento -> getCodigo();
                }
            ],
            [
                'title'  => 'TIPO DOCUMENTO',
                'render' => function($documento){
                    return $documento -> TipoDocumento -> getNombre();
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
                'title' => 'Recepción',
                'render' => function($documento){
                    return $documento -> Detalle -> getFechaRecepcion();
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($documento){
                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento -> AcuseRecepcion -> getNombre()) );

                    //$buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hRecepcion.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $documento -> getKey());
                    
                    $buttons = sprintf('
                        <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.anexos(%d)" title="Anexos del documento">
                            <i class="fa fa-fw fa-clipboard"></i>
                        </button>
                        <a class="btn btn-sm btn-circle btn-alt-success" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $documento -> getKey(), $url
                    );

                    if( user() -> can('REC.ELIMINAR.LOCAL') && $documento -> recepcionado() )
                    {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.delete_(%d)"><i class="fa fa-trash"></i></button>', $documento -> getKey());
                    }

                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax()
    {
        return url('recepcion/documentos/post-data?type=documentos');
    }

}