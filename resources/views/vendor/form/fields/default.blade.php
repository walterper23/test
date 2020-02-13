<div class="form-group row">
    @isset($label) {!! $label !!} @endisset
    <div class="{{ $widthClass }}">
    	{!! $control !!}
    </div>
</div>