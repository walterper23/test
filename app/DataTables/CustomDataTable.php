<?php

namespace App\DataTables;

use Yajra\DataTables\DataTables;

use Exception;

abstract class CustomDataTable implements DataTableInterface
{
    private $instanceTable;

    private $columns;
    private $rawColumns;
    private $removeColumns;

    protected $sourceData;
    protected $builderHtml;

    public function __construct()
    {   
        $this->columns       = array();
        $this->rawColumns    = array();
        $this->removeColumns = array();

        $this->initDatatable(new DataTables);
    }

    private function initDatatable(DataTables $datatables)
    {
        if (! request()->ajax()) {
            $this->builderHtml = $datatables->getHtmlBuilder();
            $this->initTableId()->readColumsTableForBuilder();
        } else {
            $this->initInstance($datatables)->readColumsTable()->setRawColumns()->setRemoveColumns();
        }
    }

    private function initInstance(DataTables $datatables)
    {
        $this->instanceTable = $datatables->of( $this->getSourceData() );

        return $this;
    }

    public function setTableId()
    {
        return 'table-id-datatable';
    }

    private function initTableId()
    {
        $id = $this->setTableId();

        $this->builderHtml->setTableId($id);

        return $this;
    }

    public function setSourceData()
    {
        $this->sourceData = collect([]);
    }

    private function getSourceData()
    {
        $this->setSourceData();

        return $this->sourceData;
    }
    
    private function readColumsTable()
    {
        foreach ($this->columnsTable() as $key => $column) {
            
            $name = 'column_' . $key;

            // Config *render* attribute. Using value *data* if it's not set.
            $render = isset($column['render']) ? $column['render'] : '{{$'.$column['data'].'}}';

            $this->instanceTable->addColumn($name,$render);

            $this->rawColumns[] = $name;
        }

        return $this;
    }

    private function setRawColumns()
    {
        $this->instanceTable = $this->instanceTable->rawColumns( $this->rawColumns );

        return $this;
    }

    private function setRemoveColumns()
    {
        $row = $this->instanceTable->results()->first();

        if ($row) {
            $removeColumns = array_keys($row->getAttributes());

            $relationsColumns = array_keys($row->relationsToArray());

            $removeColumns = array_merge($removeColumns,$relationsColumns);

            foreach ($removeColumns as $column) {
                $this->instanceTable = $this->instanceTable->removeColumn( $column );
            }
        }

        return $this;
    }

    /********************* B U I L D E R ***************************/

    private function readColumsTableForBuilder()
    {
        foreach ($this->columnsTable() as $key => $column) {
            
            $config = [];

            $config['title'] = $column['title'];
            
            $config['name'] = 'column_' . $key;

            $config['data'] = $config['name'];

            $config['searchable'] = isset($column['searchable']) ? $column['searchable'] : true;

            $config['orderable'] = isset($column['orderable']) ? $column['orderable'] : true;
            
            $this->columns[] = $config;
        }

        return $this;
    }

    public function getCustomOptionsParameters()
    {
        return array();
    }
    
    private function buildOptionsParameters()
    {
        $optionsParameters = array_merge($this->optionsParameters(), $this->getCustomOptionsParameters());
        
        $this->builderHtml = $this->builderHtml->parameters( $optionsParameters );

        return $this;
    }

    private function buildAjaxParameters()
    {
        $this->builderHtml = $this->builderHtml->ajax( $this->ajaxParameters() );
        
        return $this;
    }
    
    private function buildTable()
    {
        $this->builderHtml = $this->builderHtml->columns( $this->columns );

        return $this;
    }

    private function optionsParameters()
    {
        return config('datatables-html.options');
    }

    private function ajaxParameters()
    {
        return [
            'method' => $this->getMethodAjax(),
            'url'    => $this->getUrlAjax()
        ];
    }

    public function getMethodAjax()
    {
        return 'POST';
    }

    public function getUrlAjax()
    {
        return '/';
    }

    public function html( $attributes = [] )
    {
        return $this->buildOptionsParameters()
                    ->buildAjaxParameters()
                    ->buildTable()
                    ->builderHtml
                    ->table();
    }

    public function javascript()
    {
        return $this->builderHtml->scripts();
    }

    /**************************************************************/

    public function getData( $type = true )
    {
        return $this->instanceTable->toJSON( $type );
    }

}