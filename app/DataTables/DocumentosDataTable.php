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
    
    protected function setSourceData(){
        $this->sourceData = MDocumento::with('Detalle') -> select('DOCU_DOCUMENTO','DOCU_NUMERO_DOCUMENTO','DOCU_DETALLE') -> where('DOCU_DELETED',0) -> whereNotIn('DOCU_SYSTEM_TIPO_DOCTO',[1,2]); // Denuncias, Documentos de denuncias
    }

    protected function columnsTable(){
        return [
            [
                'title'  => '#',
                'render' => function($query){
                    return $query -> getCodigo();
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

                    $buttons .= '<a href="'.url('recepcion/documentos/'.$query->DOCU_DOCUMENTO.'/seguimiento').'" class="btn btn-xs btn-rounded btn-noborder btn-outline-primary" ><i class="fa fa-eye"></i></a>';

                    $buttons .= '<button type="button" class="btn btn-xs btn-rounded btn-noborder btn-outline-danger" onclick="hTipoDocumento.delete('.$query->DOCU_DOCUMENTO.')"><i class="fa fa-file-pdf-o"></i></button>';

                    return $buttons;
                }
            ]
        ];
    }

    protected function getUrlAjax(){
        return url('recepcion/documentos/post-data?type=documentos');
    }

}