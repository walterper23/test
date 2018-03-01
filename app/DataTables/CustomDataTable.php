<?php

namespace App\DataTables;

use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

use Exception;

class CustomDataTable {

	private $instanceTable;
	private $builderHtml;

	protected $sourceData;
	private $columns;

	public function __construct(DataTables $datatables){
		$this->instanceTable = $datatables;
		$this->builderHtml   = $datatables->getHtmlBuilder();
		$this->setSourceData();
		$this->initInstance()->initConfig();
	}

	protected function setSourceData(){
		$this->sourceData = collect([]);
	}

	protected function getSourceData(){
		return $this->sourceData;
	}
	
	private function initInstance(){
		$this->instanceTable = $this->instanceTable->of( $this->getSourceData() );
		return $this;
	}

	private function initConfig(){
		$this->columns = [];
		$this->readColumsTable()
				->setRawColumns();

	}

	private function readColumsTable(){
		foreach ($this->columnsTable() as $key => $column) {
			$config = [];

			// Config *name* attribute
			$config['name']  = $key;

			// Config *title* attribute. Using value empty if it is not set.
			$config['title'] = isset($column['title']) ? $column['title'] : '';

			$config['searchable'] = isset($column['searchable']) ? $column['searchable'] : true;

			$config['orderable'] = isset($column['orderable']) ? $column['orderable'] : true;
			
			// Config *render* attribute. Using value *data* if it's not set.
			if( isset($column['render']) ){
				$config['render'] = $column['render'];
			}else{
				$config['render'] = '{{$'.$column['data'].'}}';
			}

			$this->addColumn( $config )->pushColumn( $config )->pushRawColumn( $config['name'] );
		}

		return $this;
	}

	protected function columnsTable(){
		return [
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
        ];
	}

	private function addColumn( $config ){
		$this->instanceTable = $this->instanceTable->addColumn($config['name'],$config['render']);
		return $this;
	}

	private function pushColumn( $config ){
		$this->columns[] = $this->array_only(['data','name','title','searchable','orderable'], $config);
		return $this;
	}

	private function array_only($keys, $array){
		$result = [];
		foreach ($keys as $key) {
			if( isset($array[ $key ]) ){
				$result[ $key ] = $array[ $key ];
			}
		}
		return $result;
	}

	private function pushRawColumn( $column ){
		$this->rawColumns[] = $column;
		return $this;
	}

	protected function setRawColumns(){
		$this->instanceTable = $this->instanceTable->rawColumns( $this->rawColumns );
		return $this;
	}

	protected function removeColumns(){
		$this->instanceTable = $this->instanceTable->removeColumn( $this->removeColumns() );
		return $this;
	}

	private function setRemoveColumns(Array $columns){
		return ['*'];
	}



	/********************* B U I L D E R ***************************/
	
	private function buildOptionsParameters(){
		$this->builderHtml = $this->builderHtml->parameters( $this->optionsParameters() );
		return $this;
	}

	private function buildAjaxParameters(){
		$this->builderHtml = $this->builderHtml->ajax( $this->ajaxParameters() );
		return $this;
	}
	
	private function buildTable(){
		$this->builderHtml = $this->builderHtml->columns($this->columns);
		return $this;
	}

	protected function optionsParameters(){
		return config('datatables-html.options');
	}

	protected function ajaxParameters(){
		return [
			'method' => $this->getMethodAjax(),
			'url'    => $this->getUrlAjax()
		];
	}

	protected function getMethodAjax(){
		return 'POST';
	}

	protected function getUrlAjax(){
		return '/';
	}

	public function html( $attributes = [] ){
		return $this->buildOptionsParameters()
					->buildAjaxParameters()
					->buildTable()
					->builderHtml
					->table();
	}

	protected function templateHtml(){
		return config('datatables.table.table_view');
	}

	public function javascript(){
		return $this->builderHtml->scripts();
	}

	protected function templateJavascript(){
		return config('datatables.table.script_view');
	}

	/**************************************************************/

	public function getData( $type = true ){
		return $this->instanceTable->make( $type );
	}

}
