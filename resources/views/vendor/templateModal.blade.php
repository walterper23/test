<div class="block-header bg-primary-dark">
    <h3 class="block-title">@yield('title')</h3>
    <div class="block-options">
        <button type="button" class="btn-block-option" data="close-modal" aria-label="Close">
            <i class="si si-close"></i>
        </button>
    </div>
</div>
<div class="block-content">
    
	@yield('content')

</div>

@stack('js-script')

@stack('js-custom')