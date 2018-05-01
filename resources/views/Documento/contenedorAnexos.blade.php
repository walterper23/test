<div class="{{ $size or 'col-12' }}">
	<p class="font-size-lg">Listado de anexos:</p>
	
	@if (! empty($anexos))
		<p>{{ $anexos }}</p>
	@else
		<p class="font-size-sm text-muted">El documento no tiene anexos</p>
	@endif
</div>