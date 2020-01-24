<?php

namespace App\Presenters;

class MDocumentoPresenter extends Presenter
{
    public function getTipoDocumento()
    {
        return $this->model->getAttribute('SYTD_NOMBRE');
    }

    public function getRibbonColor()
    {
        return $this->model->getAttribute('SYTD_RIBBON_COLOR');
    }

    public function getBadgeTipoDocumento( $size = 'font-size-sm' )
    {
        return sprintf('<span class="%s badge badge-%s">%s</span>',$size, $this->getRibbonColor(), $this->getTipoDocumento());
    }
}
