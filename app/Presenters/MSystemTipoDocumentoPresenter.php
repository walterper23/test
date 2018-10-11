<?php
namespace App\Presenters;

class MSystemTipoDocumentoPresenter extends Presenter
{

    public function getBadge( $size = 'font-size-sm')
    {
        return sprintf('<span class="%s badge badge-%s">%s</span>',$size, $this->model->getRibbonColor(), $this->model->getNombre());
    }
    

}