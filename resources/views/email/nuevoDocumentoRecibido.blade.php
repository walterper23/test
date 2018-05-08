@component('vendor.mail.html.message')

Se ha recepcionado un nuevo documento clasificado como <b>{{ $documento -> TipoDocumento -> getNombre() }}</b>. A continuación, una breve descripción:

<div class="table">
	<table>
		<tr>
			<td><b>Tipo de documento</b></td>
			<td>{{ $documento -> TipoDocumento -> getNombre() }}</td>
		</tr>
		<tr>
			<td><b>Nó. documento</b></td>
			<td>{{ $documento -> getNumero() }}</td>
		</tr>
		<tr>
			<td><b>Fecha de recepción</b></td>
			<td>{{ $documento -> Detalle -> getFechaRecepcion() }}</td>
		</tr>
		<tr>
			<td><b>Asunto</b></td>
			<td>{{ $documento -> Detalle -> getDescripcion() }}</td>
		</tr>
		<tr>
			<td><b>Anexos</b></td>
			<td>{!! $documento -> Detalle -> presenter() -> getAnexos() !!}</td>
		</tr>
		<tr>
			<td><b>Observaciones</b></td>
			<td>{{ $documento -> Detalle -> getObservaciones() }}</td>
		</tr>
	</table>
</div>


@component('vendor.mail.html.button', ['url' => $url])
Ver documento
@endcomponent

Además, se adjunta el Acuse de Recepción del documento.

Saludos,<br>
{{ title( config_var('Sistema.Nombre') ) }}
@endcomponent