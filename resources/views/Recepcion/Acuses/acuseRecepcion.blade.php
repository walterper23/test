<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style type="text/css">
		
		@page { margin: 10px 30px; font-family: 'Calibri'; }
		
		* {  font-size: 11pt }

		body {
			background-image: url('/img/background/acuse-bg.png');
			background-repeat : no-repeat;
			background-size: 20% 20%;
			background-position: right center;
		}
		
    	
    	header { position: fixed; top: 0px; left: 0px; right: 0px; height: 50px; }
    	
    	footer { position: fixed; bottom: 5px; left: 0px; right: 0px; height: 15px; opacity: 0.5; }
		
		.text-center {
			text-align: center;
		}
		.text-left {
			text-align: left;
		}
		.text-right {
			text-align: right;
		}
		.bold {
			font-weight: bold;
		}

		table {
			border-spacing: 0;
			margin: 0 auto;
		}

		table td {
			padding: 2px 4px;
		}

		.odd {
			background-color: #DDD;
		}
		.even {
			background-color: transparent;
		}

		table.border {
			border: 1px #999 solid;
		}
	</style>
</head>
<body>
	
	<table width="100%" style="opacity: 0.7">
		<tr>
			<td width="10%" class="text-left">{{ Html::image('img/favicon/logo.png','',['width'=>'50']) }}</td>
			<td width="23%" class="text-left bold">{{ title(config_var('Sistema.Nombre')) }}</td>
			<td width="34%" class="text-center bold">{!! strtoupper(str_replace('\n', '<br>', config_var('Institucion.Nombre'))) !!}</td>
			<td width="33%" class="text-right">{{ Html::image(config_var('Institucion.Banner.Login'),'',['width'=>'140']) }}</td>
		</tr>
	</table>

	<h3 class="text-center">ACUSE DE RECEPCIÓN DE DOCUMENTO</h3>

	<p class="text-right"><b>ACUSE DE RECEPCIÓN:</b><br>
	{{ $acuse -> getNumero() }}<br>
	<b>Fecha y hora:</b> {{ $detalle -> presenter() -> getFechaHora() }}</p>

	<p align="justify">El presente acuse de recepción hace constar que el C. RICARDO CRUZ LEYVA ha hecho entrega del documento clasificado como DENUNCIA con los siguientes puntos informativos:</p>

	<table width="98%" class="border">
		<tr class="odd">
			<td width="30%" class="bold">TIPO DE DOCUMENTO</td>
			<td width="70%">{{ $documento -> TipoDocumento -> getNombre() }}</td>
		</tr>
		<tr class="even">
			<td class="bold">NÓ. DOCUMENTO</td>
			<td>{{ $documento -> getNumero() }}</td>
		</tr>
		<tr class="odd">
			<td class="bold">ASUNTO</td>
			<td>{{ $detalle -> getDescripcion() }}</td>
		</tr>
		<tr class="even">
			<td class="bold">MUNICIPIO</td>
			<td>{{ $detalle -> Municipio -> getNombre() }}</td>
		</tr>
		<tr class="odd">
			<td class="bold">NOMBRE DEL RESPONSABLE</td>
			<td>{{ $detalle -> getResponsable() }}</td>
		</tr>
	</table>

	<p align="justify">Además se reciben los siguientes anexos al documento y se registran las observaciones adicionales:</p>

	<table width="98%" class="border">
		<tr class="odd">
			<td width="30%" class="bold">ANEXOS</td>
			<td width="70%">{{ $detalle -> getAnexos() }}</td>
		</tr>
		<tr class="even">
			<td class="bold">OBSERVACIONES</td>
			<td>{{ $detalle -> getObservaciones() }}</td>
		</tr>
	</table>

	<p align="justify">A continuación se presenta la información de quién hace entrega del documento:</p>

	<table width="98%" class="border">
		<tr class="odd">
			<td width="30%" class="bold">NOMBRE COMPLETO</td>
			<td width="70%">{{ $detalle -> getEntregoNombre() }}</td>
		</tr>
		<tr class="even">
			<td class="bold">TÉLEFONO</td>
			<td>{{ $detalle -> getEntregoTelefono() }}</td>
		</tr>
		<tr class="odd">
			<td class="bold">CORREO ELECTRÓNICO</td>
			<td>{{ $detalle -> getEntregoEmail() }}</td>
		</tr>
		<tr class="even">
			<td class="bold">IDENTIFICACIÓN</td>
			<td>{{ $detalle -> getEntregoIdentificacion() }}</td>
		</tr>
	</table>

	<br><br><br><br>

	<table width="80%" border="0" style="margin: 0 auto;">
		<tr>
			<td width="45%" style="border-bottom: 1px #000 solid"></td>
			<td width="10%"></td>
			<td width="45%" style="border-bottom: 1px #000 solid"></td>
		</tr>
		<tr>
			<td class="text-center" style="vertical-align: top;">
				<b>Entregado por</b><br>
				{{ $detalle -> getEntregoNombre() }}
			</td>
			<td></td>
			<td class="text-center" style="vertical-align: top;">
				<b>Recibido por</b><br>
				{{ $usuario -> UsuarioDetalle -> presenter() -> nombreCompleto() }}
			</td>
		</tr>
	</table>

	<footer>
		<table width="100%">
			<tr>
				<td width="50%" class="text-left"><b>Acuse generado:</b> {{ date('Y-m-d h:i:s a') }}</td>
				<td width="50%" class="text-right">Página <b>1</b> de <b>1</b></td>
			</tr>
		</table>
	</footer>
	
</body>
</html>