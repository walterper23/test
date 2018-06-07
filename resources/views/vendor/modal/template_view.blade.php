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
@yield('content')
</div>

@stack('js-script')

<script type="text/javascript">
	'use strict';
	var anexo = new AppForm;
	$.extend(anexo, new function(){
		this.context_ = '#modal-view';
	}).init();
</script>