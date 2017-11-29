<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests\ManagerUsuarioRequest;
use Illuminate\Support\Facades\Input;
use Validator;

/* Controllers */
use App\Http\Controllers\BaseController;
use App\DataTables\UsuariosDataTable;

/* Models */
use App\Model\MUsuario;


class PanelController extends BaseController{

	public function __construct(){

	}

	public function index(){



		return view('Panel.Documentos.index');

	}

}
