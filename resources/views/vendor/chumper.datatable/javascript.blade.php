<script type="text/javascript">
    jQuery(document).ready(function(){
        
        oTable = jQuery('#{!! $id !!}').dataTable(
            {!! $options !!}
        );

        jQuery('#{!! $id !!} tbody').on( 'click', 'tr', function () {
	        $(this).toggleClass('alert-info');
	    });
    });
</script>
