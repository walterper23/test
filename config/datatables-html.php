<?php

return [
    /**
     * Default table attributes when generating the table.
     */
    'table' => [
        
        'id'    => 'dataTableBuilder',
        
        'class' => 'table table-bordered table-striped table-vcenter table-sm js-dataTable-full-pagination',

    ],

    'options' => [
    	'dom'       => '<"row"<"col-4"l><"col-4"r><"col-4"f>><"row"<"col-12"t>><"row"<"col-6"i><"col-6"p>>',
    	'pagingType' => 'full_numbers',
    	//'processing' => true,
		'saveState' => true,
		'pageLength' => '100',
        //'scrollY'=> '300',
        'deferRender' => true,
        //'pageResize' => true,
		'lengthMenu' => [[10, 20, 50, 100, -1],[10, 20, 50, 100, '- Todo -']],
		'language'  => [
            'url' => '/js/plugins/datatables/language/i18n.spanish.json'
		]
	],

    'templates' => [
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
    ]

];