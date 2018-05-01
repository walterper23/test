<div class="{{ $size or 'col-12' }}">
	<p class="font-size-lg">Escaneos:</p>
	@if( sizeof($escaneos) > 0 )		
	<table class="table table-vcenter">
        <tbody>
		@forelse( $escaneos as $escaneo )
			@php
				$url = url( sprintf('documento/local/escaneos?scan=%d', $escaneo -> getKey()) );
			@endphp
			<tr>
				<td>
					<i class="fa fa-fw fa-file-pdf-o text-danger"></i>
					<a class="text-primary" href="{{ $url }}" target="_blank">{{ $escaneo -> getNombre() }}</a>
				</td>
				<td width="25%" class="text-right">
					{{ $escaneo -> Archivo -> presenter() -> getSize('Kb') }}
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	@else
		<p class="font-size-sm text-muted">El documento no tiene escaneos</p>
	@endif

</div>