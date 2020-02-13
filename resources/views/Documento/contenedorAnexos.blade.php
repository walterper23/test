<div class="{{ $size or 'col-12' }}">
	<p class="font-size-md"><b>Listado de anexos:</b></p>
	
	@if (! empty($anexos))
		<table class="table table-vcenter">
			<tbody>
				<tr>
					<td class="font-size-sm">
						<p>{!! $anexos !!}</p>
					</td>
				</tr>
			</tbody>
		</table>
	@else
		<p class="font-size-sm text-muted">El documento no tiene anexos</p>
	@endif
</div>