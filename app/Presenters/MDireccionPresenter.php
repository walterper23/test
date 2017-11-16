<?php

namespace App\Presenters;

class MDireccionPresenter extends Presenter{

	



	public function link(){
		return '<a href="#" onclick="App.openModal({})">' . $this->model->DIRE_NOMBRE . '</a>';
	}

}