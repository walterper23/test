<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ title($nombre_acuse) }}</title>
    <style type="text/css">
        
        @page {
            header: page-header;
            footer: page-footer;
            background-image: url(/img/background/acuse-bg.png);
            background-position: right center;
        }
        
        body {
            font-family: 'Calibri';
            font-size: 10pt;
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
                <td width="25%" class="text-center">{{ Html::image(config_var('Institucion.Login.Logo.Izquierdo'),'',['width'=>'50']) }}</td>
                <td width="50%" class="text-center bold" style="color: #0088CC">
                    <h3>{{ config_var('Institucion.Nombre') }}</h3>
                </td>
                <td width="25%" class="text-center">{{ Html::image(config_var('Institucion.Login.Logo.Derecho'),'',['width'=>'150']) }}</td>
            </tr>
        </table>
    </htmlpageheader>

    <h3 class="text-center">ACUSE DE RECEPCIÓN DE DOCUMENTO</h3>

    <p class="text-right">
        <b>FOLIO DE RECEPCIÓN:</b><br>
        {{ $acuse->getNumero() }}<br>
        <b>Fecha de recepción:</b> {{ $detalle->getFechaRecepcion() }}<br>
        <b>Fecha de captura:</b> {{ $detalle->presenter()->getFechaHora() }}
    </p>

    <p align="justify">
        El presente acuse de recepción hace constar que
        @if (! empty($detalle->getEntregoNombre()) )
        el(la) <b>C. {{ $detalle->getEntregoNombre() }}</b>
        @else
        se
        @endif
        ha hecho entrega del documento clasificado como <b>{{ $documento->TipoDocumento->getNombre() }}</b> y que ha sido recepcionado con los siguientes puntos informativos:
    </p>

    <table class="border">
        <tr class="odd">
            <td width="25%" class="bold">Tipo de documento</td>
            <td width="75%">
                {{ $documento->TipoDocumento->getNombre() }}
                @if ( $documento->getTipoDocumento() == 2 )
                &nbsp;&nbsp;&nbsp;&nbsp;<b>Expediente</b>
                &nbsp;&nbsp;&nbsp;&nbsp;{{ $documento->DocumentoDenuncia->Denuncia->getNoExpediente() }}
                @endif
            </td>
        </tr>
        <tr class="even">
            <td class="bold">Nó. documento</td>
            <td>{{ $documento->getNumero() }}</td>
        </tr>
        <tr class="odd">
            <td class="bold">Asunto</td>
            <td>{{ $detalle->getDescripcion() }}</td>
        </tr>
        <tr class="even">
            <td class="bold">Municipio</td>
            <td>{{ $detalle->Municipio->getNombre() }}</td>
        </tr>
        <tr class="odd">
            <td class="bold">Nombre del responsable</td>
            <td>{{ $detalle->getResponsable() }}</td>
        </tr>
    </table>

    <p align="justify">A continuación se presenta la información de quién hace entrega del documento:</p>

    <table class="border">
        <tr class="odd">
            <td width="25%" class="bold">Nombre completo</td>
            <td width="75%">{{ $detalle->getEntregoNombre() }}</td>
        </tr>
        <tr class="even">
            <td class="bold">Teléfono</td>
            <td>{{ $detalle->getEntregoTelefono() }}</td>
        </tr>
        <tr class="odd">
            <td class="bold">Correo electrónico</td>
            <td>{{ $detalle->getEntregoEmail() }}</td>
        </tr>
        <tr class="even">
            <td class="bold">Identificación</td>
            <td>{{ $detalle->getEntregoIdentificacion() }}</td>
        </tr>
    </table>
    
    <p align="justify">Además se reciben los siguientes anexos al documento y se registran las observaciones adicionales:</p>

    <table class="border">
        <tr class="odd">
            <td width="25%" class="bold">Anexos</td>
            <td width="75%">{!! $detalle->presenter()->getAnexos() !!}</td>
        </tr>
        <tr class="even">
            <td class="bold">Observaciones</td>
            <td>{{ $detalle->getObservaciones() }}</td>
        </tr>
    </table>

    <br><br><br><br><br>

    <table width="80%">
        <tr>
            @if (! empty($detalle->getEntregoNombre()) )
            <td width="45%" style="border-bottom: 1pt #000000 solid"></td>
            <td width="10%"></td>
            @endif
            <td width="45%" style="border-bottom: 1pt #000000 solid"></td>
        </tr>
        <tr>
            @if (! empty($detalle->getEntregoNombre()) )
            <td class="text-center" style="vertical-align: top;">
                <b>Entregado por</b><br>
                {{ $detalle->getEntregoNombre() }}
            </td>
            <td></td>
            @endif
            <td class="text-center" style="vertical-align: top;">
                <b>Recibido por</b><br>
                {{ $usuario->UsuarioDetalle->presenter()->getNombreCompleto() }}<br>
                {{ $usuario->getDescripcion() }}
            </td>
        </tr>
    </table>

    <htmlpagefooter name="page-footer">
        <table style="font-size: 6pt">
            <tr>
                <td width="30%"></td>
                <td width="70%" class="text-right">
                    {{ sprintf('%s. %s. %s.',config_var('Institucion.Nombre'),config_var('Institucion.Direccion'),config_var('Institucion.Telefonos')) }}<br>   
                </td>
            </tr>
            <tr>
                <td class="text-left">
                    {{ title(config_var('Sistema.Nombre') . ' ' . config_var('Sistema.Version')) }}<br>   
                    <b>Acuse generado:</b> {{ date('Y-m-d h:i:s a') }}</td>
                <td class="text-right">Página <b>{PAGENO}</b> de <b>{nb}</b></td>
            </tr>
        </table>
    </htmlpagefooter>
    
</body>
</html>