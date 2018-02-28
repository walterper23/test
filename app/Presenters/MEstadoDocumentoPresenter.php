<?php
namespace App\Presenters;

class MEstadoDocumentoPresenter extends Presenter {

	public function badge(){
        switch ( $this->model->getKey() ) {
            case 1:
                return '<span class="uk-badge uk-badge-primary">' . $this->model->getNombre() . '</span>';
                break;
            case 2:
                return '<span class="uk-badge uk-badge-warning">' . $this->model->getNombre() . '</span>';
                break;
            case 3:
                return '<span class="uk-badge uk-badge-warning">' . $this->model->getNombre() . '</span>';
                break;
            case 4:
                return '<span class="uk-badge uk-badge-warning">' . $this->model->getNombre() . '</span>';
                break;
            case 5:
                return '<span class="uk-badge uk-badge-danger">' . $this->model->getNombre() . '</span>';
                break;
            case 6:
                return '<span class="uk-badge uk-badge-success">' . $this->model->getNombre() . '</span>';
                break;
            default: break;
        }
    }

}