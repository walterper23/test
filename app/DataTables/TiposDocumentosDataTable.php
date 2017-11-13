<?php

namespace App\DataTables;

use App\Model\Catalogo\MTipoDocumento;

class TiposDocumentosDataTable extends CustomDataTable{


    protected function setSourceData(){
        $this->sourceData = MTipoDocumento::selectRaw('TIDO_TIPO_DOCUMENTO as id, TIDO_NOMBRE_TIPO as nombre, TIDO_CREATED_AT as fecha, TIDO_VALIDAR, TIDO_ENABLED')->where('TIDO_DELETED',0)->get();
    }


    protected function getUrlAjax(){
        return url('configuracion/catalogos/tipos-documentos/post-data');
    }
    
}
