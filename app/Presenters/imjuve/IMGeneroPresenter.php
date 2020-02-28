<?php
namespace App\Presenters;

class IMGeneroPresenter extends Presenter
{
    public function getIcon( $size = 'font-size-sm' )
    {
        return sprintf('<span class="%s si %s"></span>',$size, $this->model->getIcon());
    }
}
