<?php
namespace App\Http\Controllers\DataTables;

use DataTables;
use Yajra\DataTables\Html\Builder;

use Exception;

class CustomDataTablesController {

	private $instanceTable;
	private $config;
	private $whitelistColumns;
	private $rawColumns;

	private $builder;

	public function __construct( $source, Array $config = [] ){
			
		$this->instanceTable = new DataTables();

		$this->setSourceData($source)->setConfig($config)->initConfig();
	}

	public function setSourceData( $source ){
		$this->instanceTable = $this->instanceTable::make( $source );
		return $this;
	}

	public function setConfig( $config ){
		$this->config = $config;
		return $this;
	}

	private function initConfig(){

		if( isset($this->config['config']) ){

		}

		if( isset($this->config['columns']) && sizeof($this->config['columns']) > 0 ){
			$this->initReadColumns($this->config['columns']);	
		}else{
			throw new Exception(' Debe realizar la especificación de al menos una columna ');
		}

		if( isset($this->config['addColumns']) ){
			
		}

		if( isset($this->config['removeColumns']) ){
			$this->removeColumns($this->config['removeColumns']);
		}

		$this->setWhiteListColumns()->setRawColumns();

	}

	private function initReadColumns( Array $columns ){
		foreach($columns as $column){

			$data = $column['data'];

			if( isset($column['transform']) ){
				$transform = $column['transform'];
			}else{
				$transform = '{{$' . $data . '}}'; 
			}

			$this->instanceTable->editColumn( $data, $transform );

			if( isset($column['config']) )
				$this->configColumn($data, $column['config']);

			$this->addColumnToWhiteList( $column['data'] );

		}
	}


	private function configColumn( $column, $config ){
		if( isset($config['raw']) && $config['raw'] == true )
			$this->rawColumns[] = $column;

		if( isset($config['searchable']) && $config['searchable'] == false )
			$this->unsearchableColumns[] = $column;

	}


	private function addColumnToWhiteList($column){
		$this->whitelist[] = $column;
	}

	private function setWhiteListColumns(){
		if( !is_null($this->whitelist) )
			$this->instanceTable->whitelist( array_values($this->whitelist) );
		return $this;
	}

	private function setRawColumns(){
		if( !is_null($this->rawColumns) )
			$this->instanceTable->rawColumns( array_values($this->rawColumns) );
		return $this;
	}

	private function removeColumns(Array $columns){
		foreach($columns as $column){
			$this->instanceTable->removeColumn( $column );
		}
		return $this;
	}


	/********************* B U I L D E R ***********************************/
	private function initBuilder(){
		$this->builder = $this->instanceTable->getHtmlBuilder();
		$this->buildTable();
	}

	private function buildTable(){

		$this->builder->columns([
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

	public function getTable(Array $attributes = null ){
		if( is_null($this->builder) ) $this->initBuilder();
		return $this->builder->table( $attributes );
	}

	public function getScript(){
		if( is_null($this->builder) ) $this->initBuilder();
		return $this->build->scripts();
	}


}
