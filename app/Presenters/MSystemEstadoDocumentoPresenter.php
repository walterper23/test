<?php
namespace App\Presenters;

class MSystemEstadoDocumentoPresenter extends Presenter
{
	public function getBadge(){
        switch ( $this->model->getKey() ) {
            case 1: // En captura
                return sprintf('<span class="badge badge-default">%s</span>',$this->model->getNombre());
                break;
            case 2: // Recepcionado
                return sprintf('<span class="badge badge-primary">%s</span>',$this->model->getNombre());
                break;
            case 3: // En seguimiento
                return sprintf('<span class="badge badge-danger">%s</span>',$this->model->getNombre());
                break;
            case 4: // Finalizado
                return sprintf('<span class="badge badge-success">%s</span>',$this->model->getNombre());
                break;
            case 5: // Rechazado
                return sprintf('<span class="badge badge-warning">%s</span>',$this->model->getNombre());
                break;
            case 6: // Eliminado
                return sprintf('<span class="badge badge-default">%s</span>',$this->model->getNombre());
                break;
            default: break;
        }
    }

}