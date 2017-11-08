<script type="text/javascript">
    'use strict';
    jQuery(document).ready(function(){
        
        var oTable = jQuery('#{!! $id !!}').dataTable(
            {!! $options !!}
        );

        $('#importbutton').click( function () {
			$('input:checked', oTable.fnGetNodes()).each(function(){
				var rowIndex = oTable.fnGetPosition( $(this).closest('tr')[0] );
				alert(oTable.fnGetData(rowIndex));
			})
		})

    })
</script>
