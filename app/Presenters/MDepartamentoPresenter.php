<?php

namespace App\Presenters;

class MDepartamentoPresenter extends Presenter{

	

	public function link(){
		return '<a href="#" onclick="App.openModal({})">' . $this->model->DEPA_NOMBRE . '</a>';
	}


}