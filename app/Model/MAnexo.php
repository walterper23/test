<?php
namespace App\Model\Catalogo;

/* Models */
use App\Model\BaseModel;

/* Presenter */
use App\Presenters\MAnexoPresenter;

class MAnexo extends BaseModel {
    
	protected $table        = 'cat_anexos';
	protected $primaryKey   = 'ANEX_ANEXO';
	
	protected $fieldEnabled = 'ANEX_ENABLED';
	protected $fieldDeleted = 'ANEX_DELETED';

    /* Presenter */    
    public function presenter(){
        return new MAnexoPresenter($this);
    }

}