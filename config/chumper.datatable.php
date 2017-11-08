<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Table specific configuration options.
    |--------------------------------------------------------------------------
    |
    */

    'table' => array(

        /*
        |--------------------------------------------------------------------------
        | Table class
        |--------------------------------------------------------------------------
        |
        | Class(es) added to the table
        | Supported: string
        |
        */

        'class' => 'table table-bordered table-striped table-vcenter table-sm',

        /*
        |--------------------------------------------------------------------------
        | Table ID
        |--------------------------------------------------------------------------
        |
        | ID given to the table. Used for connecting the table and the Datatables
        | jQuery plugin. If left empty a random ID will be generated.
        | Supported: string
        |
        */

        'id' => '',

        /*
        |--------------------------------------------------------------------------
        | DataTable options
        |--------------------------------------------------------------------------
        |
        | jQuery dataTable plugin options. The array will be json_encoded and
        | passed through to the plugin. See https://datatables.net/usage/options
        | for more information.
        | Supported: array
        |
        */

        'options' => array(

            "dom" => '<"row"<"col-4"l><"col-4"r><"col-4"f>><"row"<"col-12"t>><"row"<"col-6"i><"col-6"p>>',

            'pagingType' => 'full_numbers',
            
            'processing' => false,

            'serverSide' => true,

            'ajax' => [ 'type' => 'POST' ],

            'stateSave' => true,

            'pageLength' => '100',

            'lengthMenu' => [[10, 20, 50, 100, -1],[10, 20, 50, 100, '- Todo -']],

            'language' => [ 'url' => '/js/plugins/datatables/language/i18n.spanish.lang' ]
        ),

        /*
        |--------------------------------------------------------------------------
        | DataTable callbacks
        |--------------------------------------------------------------------------
        |
        | jQuery dataTable plugin callbacks. The array will be json_encoded and
        | passed through to the plugin. See https://datatables.net/usage/callbacks
        | for more information.
        | Supported: array
        |
        */

        'callbacks' => array(),

        /*
        |--------------------------------------------------------------------------
        | Skip javascript in table template
        |--------------------------------------------------------------------------
        |
        | Determines if the template should echo the javascript
        | Supported: boolean
        |
        */

        'noScript' => true,


        /*
        |--------------------------------------------------------------------------
        | Table view
        |--------------------------------------------------------------------------
        |
        | Template used to render the table
        | Supported: string
        |
        */

        'table_view' => 'chumper.datatable::template',


        /*
        |--------------------------------------------------------------------------
        | Script view
        |--------------------------------------------------------------------------
        |
        | Template used to render the javascript
        | Supported: string
        |
        */

        'script_view' => 'chumper.datatable::javascript',
    ),


    /*
    |--------------------------------------------------------------------------
    | Engine specific configuration options.
    |--------------------------------------------------------------------------
    |
    */

    'engine' => array(

        /*
        |--------------------------------------------------------------------------
        | Search for exact words
        |--------------------------------------------------------------------------
        |
        | If the search should be done with exact matching
        | Supported: boolean
        |
        */

        'exactWordSearch' => false,

    ),
    /*
    |--------------------------------------------------------------------------
    | Allow overrides Datatable core classes
    |--------------------------------------------------------------------------
    |
    */
    'classmap' => array(
        'CollectionEngine' => 'Chumper\Datatable\Engines\CollectionEngine',
        'QueryEngine' => 'Chumper\Datatable\Engines\QueryEngine',
        'Table' => 'Chumper\Datatable\Table',
    )
);
