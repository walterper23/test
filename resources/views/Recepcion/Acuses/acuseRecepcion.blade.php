<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ title($nombre_acuse) }}</title>
    <style type="text/css">
        
        @page {
            header: page-header;
            footer: page-footer;
        }
        
        body {
            font-family: 'Calibri';
            font-size: 10pt;
            background-image: url('img/background/background.png');
            background-repeat : no-repeat;
            background-position: right center;
        }
        
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
            width: 100%;
            border-spacing: 0;
            margin: 0 auto;
        }

        table td {
            padding: 3px 5px;
        }

        .odd {
            background-color: rgb(240,240,240);
        }

        .even {
            background-color: rgb(252,252,252);
        }

        .border {
            border-top: 1px solid #AAA;
            border-bottom: 1px solid #AAA;
        }
    </style>
</head>
<body>

    <htmlpageheader name="page-header">
        <table>
            <tr>
                <td width="20%" class="text-center">{{ Html::image('img/background/escudo-qroo.png','',['width'=>'50']) }}</td>
                <td width="60%" class="text-center bold" style="color: #0088CC">
                    <h3>{!! strtoupper(str_replace(['\n','á','é','í','ó','ú'],['<br>','a','e','i','o','u'], config_var('Institucion.Nombre'))) !!}</h3>
                </td>
                <td width="20%" class="text-center">{{ Html::image('img/background/ppa.png','',['width'=>'150']) }}</td>
            </tr>
        </table>
    </htmlpageheader>
    
    <br>

    <h3 class="text-center">ACUSE DE RECEPCIÓN DE DOCUMENTO</h3>

    <p class="text-right"><b>ACUSE DE RECEPCIÓN:</b><br>
    {{ $acuse -> getNumero() }}<br>
    <b>Fecha y hora:</b> {{ $detalle -> presenter() -> getFechaHora() }}</p>

    <p align="justify">
        El presente acuse de recepción hace constar que
        @if (! empty($detalle -> getEntregoNombre()) )
        el(la) <b>C. {{ $detalle -> getEntregoNombre() }}</b>
        @else
        se
        @endif
        ha hecho entrega del documento clasificado como <b>{{ $documento -> TipoDocumento -> getNombre() }}</b> y que ha sido recepcionado con los siguientes puntos informativos:
    </p>

    <table class="border">
        <tr class="odd">
            <td width="25%" class="bold">Tipo de documento</td>
            <td width="75%">
                {{ $documento -> TipoDocumento -> getNombre() }}
                @if ( $documento -> getTipoDocumento() == 2 )
                &nbsp;&nbsp;&nbsp;&nbsp;<b>Expediente</b>
                &nbsp;&nbsp;&nbsp;&nbsp;{{ $documento -> DocumentoDenuncia -> Denuncia -> getNoExpediente() }}
                @endif
            </td>
        </tr>
        <tr class="even">
            <td class="bold">Nó. documento</td>
            <td>{{ $documento -> getNumero() }}</td>
        </tr>
        <tr class="odd">
            <td class="bold">Asunto</td>
            <td>{{ $detalle -> getDescripcion() }}</td>
        </tr>
        <tr class="even">
            <td class="bold">Municipio</td>
            <td>{{ $detalle -> Municipio -> getNombre() }}</td>
        </tr>
        <tr class="odd">
            <td class="bold">Nombre del responsable</td>
            <td>{{ $detalle -> getResponsable() }}</td>
        </tr>
    </table>

    <p align="justify">A continuación se presenta la información de quién hace entrega del documento:</p>

    <table class="border">
        <tr class="odd">
            <td width="30%" class="bold">Nombre completo</td>
            <td width="70%">{{ $detalle -> getEntregoNombre() }}</td>
        </tr>
        <tr class="even">
            <td class="bold">Teléfono</td>
            <td>{{ $detalle -> getEntregoTelefono() }}</td>
        </tr>
        <tr class="odd">
            <td class="bold">Correo electrónico</td>
            <td>{{ $detalle -> getEntregoEmail() }}</td>
        </tr>
        <tr class="even">
            <td class="bold">Identificación</td>
            <td>{{ $detalle -> getEntregoIdentificacion() }}</td>
        </tr>
    </table>
    
    <p align="justify">Además se reciben los siguientes anexos al documento y se registran las observaciones adicionales:</p>

    <table class="border">
        <tr class="odd">
            <td width="30%" class="bold">Anexos</td>
            <td width="70%">{!! $detalle -> presenter() -> getAnexos() !!}</td>
        </tr>
        <tr class="even">
            <td class="bold">Observaciones</td>
            <td>{{ $detalle -> getObservaciones() }}</td>
        </tr>
    </table>

    <br><br><br><br>

    <table width="80%">
        <tr>
            @if (! empty($detalle -> getEntregoNombre()) )
            <td width="45%" style="border-bottom: 1pt #000000 solid"></td>
            <td width="10%"></td>
            @endif
            <td width="45%" style="border-bottom: 1pt #000000 solid"></td>
        </tr>
        <tr>
            @if (! empty($detalle -> getEntregoNombre()) )
            <td class="text-center" style="vertical-align: top;">
                <b>Entregado por</b><br>
                {{ $detalle -> getEntregoNombre() }}
            </td>
            <td></td>
            @endif
            <td class="text-center" style="vertical-align: top;">
                <b>Recibido por</b><br>
                {{ $usuario -> UsuarioDetalle -> presenter() -> nombreCompleto() }}<br>
                {{ $usuario -> UsuarioDetalle -> Puesto -> getNombre() }}
            </td>
        </tr>
    </table>
    
    <htmlpagefooter name="page-footer">
        <table style="font-size: 6pt">
            <tr>
                <td width="50%" class="text-left">
                    {{ title(config_var('Sistema.Nombre')) }}<br>   
                    <b>Acuse generado:</b> {{ date('Y-m-d h:i:s a') }}</td>
                <td width="50%" class="text-right">Página <b>{PAGENO}</b> de <b>{nb}</b></td>
            </tr>
        </table>
    </htmlpagefooter>
    
</body>
</html>