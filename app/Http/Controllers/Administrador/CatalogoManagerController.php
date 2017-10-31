<?php
namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\DataTables\CustomDataTablesController;

use App\Model\Catalogo\MTipoDocumento;
use App\Model\MDocumento;


class CatalogoManagerController extends BaseController{


	public function index(){

		$table = $this->makeTable();

		return view('Administrador.Catalogos.index')->with('table', $table);

	}

	private function makeTable(){

		$data = MDocumento::with('documentoDetalle')->select('DOCU_DOCUMENTO AS id','DOCU_DESCRIPCION AS nombre', 'DOCU_CREATED_AT AS fecha')->get();

		$config['config'] = [
			'rowID' => 'id',
			'rowClass' => function ($sql) {
			    return $sql->id % 2 == 0 ? 'alert-success' : 'alert-warning';
			},
			'rowAttributes' => function ($sql) {
			    return [
			    	'color' => 'color-' . $sql->id
			    ];
			}
		];

		$config['columns'] = [

			array(
				'label' => '#',
				'orden' => 1,
				'data' => 'id',
				'transform' => function($sql){
					return '<b>' . $sql->id . '</b>';
				},
				'config' => [
					'raw' => true,
					'searchable' => true,
				]

			),
			array(
				'label' => 'Nombre',
				'orden' => 2,
				'data' => 'nombre',
				'transform' => function($sql){
					return $sql->nombre;
				},

			),
			array(
				'label' => 'Fecha',
				'orden' => 3,
				'data' => 'fecha',
				'transform' => function($sql){
					return $sql->fecha;
				},
				'config' => [
					'searchable' => true,
				]

			),
		];


		$config['addColumns'] = [
			array(


			)
		];

		$config['removeColumns'] = [];

		return new CustomDataTablesController( $data, $config );

	}

	public function postData(){
		return $this->makeTable()->getData();
	}

}
