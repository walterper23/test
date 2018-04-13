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
        $this -> sourceData = MDocumento::with('TipoDocumento','Detalle') -> select('DOCU_DOCUMENTO','DOCU_NUMERO_DOCUMENTO','DOCU_DETALLE','DOCU_SYSTEM_TIPO_DOCTO') -> existente() -> noGuardado() -> whereNotIn('DOCU_SYSTEM_TIPO_DOCTO',[1,2]) -> get(); // Denuncias, Documentos de denuncias
    }

    protected function columnsTable()
    {
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return $query -> getCodigo();
                }
            ],
            [
                'title'  => 'TIPO DOCUMENTO',
                'render' => function($query){
                    return $query -> TipoDocumento -> getNombre();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'data'   => 'DOCU_NUMERO_DOCUMENTO'
            ],
            [
                'title'  => 'ASUNTO',
                'render' => function($query){
                    return $query -> Detalle -> getDescripcion();
                }
            ],
            [
                'title' => 'Recepción',
                'render' => function($query){
                    return $query -> Detalle -> getFechaRecepcion();
                }
            ],
            [
                'title'  => 'Opciones',
                'render' => function($query){
                    $buttons = '';

                    $buttons .= sprintf('<button type="button" class="btn btn-sm btn-circle btn-alt-primary" onclick="hRecepcion.view(%d)" title="Ver documento"><i class="fa fa-fw fa-eye"></i></button>', $query -> getKey());
                    
                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.view(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $query -> getKey());

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-success" onclick="hRecepcion.view(%d)" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></button>', $query -> getKey());



                    //$buttons .= '<button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcion.delete('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-file-pdf-o"></i></button>';

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