<?php
namespace App\Presenters;
use Carbon\Carbon;
class IMAfiliacionPresenter extends Presenter
{
    public function getFnFormat(){
        if(!is_null($this->model->getFechaNacimiento()) && $this->model->getFechaNacimiento()!='0000-00-00'){
            $date = Carbon::createFromFormat('Y-m-d', $this->model->getFechaNacimiento());
            return $date->format('d-m-Y');
        }
    }
    public function getContacto(){
        return 'Télefono: '.$this->model->getTelefono().'<br>Correo: '.$this->model->getCorreo();
    }
}
