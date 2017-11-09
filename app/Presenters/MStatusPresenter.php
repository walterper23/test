<?php

namespace App\Presenters;

class MStatusPresenter extends Presenter{

	public function badge(){
        $status = '';
        switch ( $this->getStatusID() ) {
            case 1:
                $status = '<span class="uk-badge uk-badge-primary">' . $this->status->getNombre() . '</span>';
                break;
            case 2:
                $status = '<span class="uk-badge uk-badge-warning">' . $this->status->getNombre() . '</span>';
                break;
            case 3:
                $status = '<span class="uk-badge uk-badge-warning">' . $this->status->getNombre() . '</span>';
                break;
            case 4:
                $status = '<span class="uk-badge uk-badge-warning">' . $this->status->getNombre() . '</span>';
                break;
            case 5:
                $status = '<span class="uk-badge uk-badge-danger">' . $this->status->getNombre() . '</span>';
                break;
            case 6:
                $status = '<span class="uk-badge uk-badge-success">' . $this->status->getNombre() . '</span>';
                break;
            default: break;
        }
        return $status;
    }

}