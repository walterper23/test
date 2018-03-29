<?php
namespace App\Presenters;

class MSistemaTipoDocumentoPresenter extends Presenter {

	


	public function getColorRibbon()
	{
		switch ($this -> model -> getKey()) {
			case 1: return 'primary'; break; // Denuncia
			case 2: return 'info'; break; // Documento para denuncia
			case 3: return 'success'; break; // Ficha
			case 4: return 'warning'; break; // Circular
			case 5: return 'danger'; break; // Invitación
			default: return 'primary'; break;
		}
	}


}