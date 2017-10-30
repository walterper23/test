<?php
namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\DataTables\CustomDataTablesController;

use App\Model\Catalogo\MTipoDocumento;


class CatalogoManagerController extends BaseController{


	public function index(){

		$table = $this->makeTable();

		return view('Administrador.Catalogos.index')->with('table', $table);

	}

	private function makeTable(){

		$data = MTipoDocumento::select('TIDO_TIPO_DOCUMENTO AS id','TIDO_NOMBRE_TIPO')->get();

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
				'data' => 'TIDO_TIPO_DOCUMENTO',
				'setData' => function($sql){
					return $sql->id;
				},
				'transform' => function(){

				},
				'config' => [
					'searc'
				]

			),
			array(
				'label' => 'Nombre',
				'orden' => 2,
				'data' => 'TIDO_NOMBRE',
				'setData' => function($sql){
					return 'prefix :: ' . $sql->nombre;
				},
				'transform' => function(){

				}

			),
		];


		$config['addColumns'] = [

			array(



			)

		];

		$config['removeColumns'] = [
			'fecha'
		];


		return new CustomDataTablesController( $data, $config );

	}

	public function postData(){
		return $this->makeTable()->getData();
	}

}
