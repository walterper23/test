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
        return 'dataTableBuilder';
    }

    private function initTableId()
    {
        $id = $this->setTableId();

        $this->builderHtml->setTableId($id);

        return $this;
    }

    private function getSourceData()
    {
        $this->setSourceData();

        if (is_array($search = request('search')) && !empty($search['value'])) {
            $this->applyGlobalFilter($search['value']);
        }

        return $this->sourceData;
    }

    private function applyGlobalFilter($search)
    {
        $columns = $this->columnsTable();
        $this->sourceData->where(function($query) use ($columns, $search){
            foreach ($columns as $key => $column) {
                if ($column['data'] != false) $query->orWhere($column['data'],'like',"%$search%");
            }
        });
    }
    
    private function readColumsTable()
    {
        foreach ($this->columnsTable() as $key => $column) {
            
            $name = 'column_' . $key;

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
        // $row = $this->instanceTable->results()->first();

        // if ($row) {
        //     $removeColumns = array_keys($row->getAttributes());

        //     $relationsColumns = array_keys($row->relationsToArray());

        //     $removeColumns = array_merge($removeColumns,$relationsColumns);

        //     foreach ($removeColumns as $column) {
        //         $this->instanceTable = $this->instanceTable->removeColumn( $column );
        //     }
        // }

        return $this;
    }

    /*----------------------- B U I L D E R ------------------------*/

    private function readColumsTableForBuilder()
    {
        $configs = config('datatables-html.configs');

        foreach ($this->columnsTable() as $key => $column) {
            
            $config = $configs['default'];

            $config['title'] = $column['title'];
            
            $config['name'] = 'column_' . $key;

            $config['data'] = $config['name'];

            if (isset($column['config'])) {
                $config = array_merge($config, $configs[$column['config']]);
            }

            if (isset($column['class'])) {
                $config['className'] = $column['class'];
            }

            if (isset($column['searchable'])) {
                $config['searchable'] = $column['searchable'];
            }

            if (isset($column['orderable'])) {
                $config['orderable'] = $column['orderable'];
            }

            if (isset($column['width'])) {
                $config['style'] = sprintf('width: %s;', $column['width']);
            }
            
            $this->columns[] = $config;
        }

        return $this;
    }

    /*-----------------------------------------------------------*/

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

    public function html( $attributes = array() )
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
