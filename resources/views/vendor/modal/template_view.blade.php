<div class="block-header {{ $headerColor or 'bg-primary' }}">
    <h3 class="block-title">@yield('title')</h3>
    <div class="block-options">
        <button type="button" class="btn-block-option" data-close="modal" aria-label="Close">
            <i class="si si-close"></i>
        </button>
    </div>
</div>

@stack('css-style')

@stack('css-custom')

<div class="block-content">
    @each('vendor.modal.view_detail',$detalles,'detalle')
</div>

@stack('js-script')

<script type="text/javascript">
	'use strict';
	var modal_view = new AppForm;
	$.extend(modal_view, new function(){
		this.context_ = '#modal-view';
	}).init();
</script>