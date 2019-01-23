<?php
namespace App\Presenters;

class MDocumentoForaneoPresenter extends Presenter
{
    public function getBadgePendiente()
    {
        return '<span class="badge badge-danger"><i class="fa fa-fw fa-times"></i> Pendiente</span>';
    }

    public function getBadgeEnEspera()
    {
        return '<span class="badge badge-warning"><i class="fa fa-fw fa-hourglass-start"></i> En espera</span>';
    }

    public function getBadgeNoEnviado()
    {
        return '<span class="badge badge-danger"><i class="fa fa-fw fa-car"></i> Aún no enviado</span>';
    }

    public function getBadgeEnviado()
    {
        return sprintf('<span class="badge badge-primary" title="%s">Enviado <i class="fa fa-fw fa-car"></i></span>',$this->model->getFechaEnviado());
    }

    public function getBadgeRecibido()
    {
        return sprintf('<span class="badge badge-primary" title="%s"><i class="fa fa-fw fa-folder"></i> Recibido</span>',$this->model->getFechaRecibido());
    }

    public function getBadgeValidado()
    {
        return sprintf('<span class="badge badge-success" title="%s"><i class="fa fa-fw fa-check"></i> Validado</span>',$this->model->getFechaValidado());
    }

    public function getBadgeRecepcionado()
    {
        return sprintf('<span class="badge badge-success" title="%s"><i class="fa fa-fw fa-check"></i> Recepcionado</span>',$this->model->getFechaRecepcionado());
    }

}