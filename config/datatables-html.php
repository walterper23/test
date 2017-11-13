<?php

return [
    /**
     * Default table attributes when generating the table.
     */
    'table' => [
        
        'id'    => 'dataTableBuilder',
        
        'class' => 'table table-bordered table-striped table-vcenter table-sm',

     	/*
        |--------------------------------------------------------------------------
        | Table view
        |--------------------------------------------------------------------------
        |
        | Template used to render the table
        | Supported: string
        |
        */

        'table_view' => 'datatables::template',

        /*
        |--------------------------------------------------------------------------
        | Script view
        |--------------------------------------------------------------------------
        |
        | Template used to render the javascript
        | Supported: string
        |
        */

        'script_view' => 'datatables::script',
    ],

];
