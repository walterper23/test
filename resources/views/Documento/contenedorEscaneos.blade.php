<div class="{{ $size or 'col-12' }}">
	<p class="font-size-lg">Escaneos:</p>
	
	@forelse( $escaneos as $escaneo )

		@php
			$url = url( sprintf('documento/local/escaneos?scan=%d', $escaneo -> getKey()) );
		@endphp

		<div class="col-12">
			<i class="fa fa-fw fa-file-pdf-o text-danger"></i>
			<a class="text-primary" href="{{ $url }}" target="_blank">{{ $escaneo -> getNombre() }}</a>
			<span class="pull-right">{{ $escaneo -> Archivo -> presenter() -> getSize('Kb') }}</span>
		</div>
	@empty
		<p class="text-danger">El documento no tiene escaneos</p>
	@endforelse

</div>