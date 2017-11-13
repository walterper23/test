<?php

namespace App\DataTables;

use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

use Exception;

class CustomDataTable {

	private $instanceTable;
	private $builderHtml;

	protected $sourceData;

	public function __construct(DataTables $datatables){
		$this->instanceTable = $datatables;
		$this->builderHtml   = $datatables->getHtmlBuilder();
		$this->setSourceData();
		$this->initConfig();
	}

	protected function setSourceData(){
		$this->sourceData = App\User::all();
	}

	protected function getSourceData(){
		return $this->sourceData;
	}
	
	private function initInstance(){
		$this->instance = $this->instance->of( $this->getSourceData() );
	}

	private function initConfig(){
		
	}

	protected function rawColumns(){
		$this->instanceTable->rawColumns( $this->rawColumns() );
		return $this;
	}

	private function setRawColumns(){
		return [];
	}

	protected function removeColumns(){
		$this->instanceTable->removeColumn( $this->removeColumns() );
		return $this;
	}

	private function setRemoveColumns(Array $columns){
		return [];
	}



	/********************* B U I L D E R ***********************************/
	private function initBuilder(){
		$this->builderHtml = $this->instanceTable->getHtmlBuilder();
		$this->buildTable();
	}

	private function buildTable(){

		$this->builderHtml->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
        ]);

	}	

	public function getData( $type = 'Array' ){

		if( $type == 'Array' ){
			return $this->instanceTable->make();
		}

		if( $type == 'JSON' ){
			return $this->instanceTable->make(true);
		}

	}

	

	protected function configParameters(){
		return [
			'dom'       => 'Bfrtip',
			'buttons'   => ['reload','export'],
			'saveState' => true,
			'language'  => [
				'url' => '/js/plugins/datatables/language/i18n.spanish.lang'
			]
		];
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
		if( is_null($this->builderHtml) ) $this->initBuilder();
		return $this->builderHtml->table( $attributes );
	}

	protected function templateHtml(){
		return Config::get('datatables.table.table_view');
	}

	public function javascript(){
		if( is_null($this->builder) ) $this->initBuilder();
		return $this->build->scripts();
	}

	protected function templateJavascript(){
		return Config::get('datatables.table.script_view');
	}


}
