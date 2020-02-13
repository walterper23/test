<div class="{{ $size or 'col-12' }}">
    <p class="font-size-md"><b>Escaneos:</b></p>
    @if( sizeof($escaneos) > 0 )        
    <table class="table table-vcenter">
        <tbody>
        @foreach( $escaneos as $escaneo )
            @php
                $url = url( sprintf('documento/local/escaneos?scan=%d', $escaneo->getKey()) );
            @endphp
            <tr>
                <td class="font-size-sm">
                    <i class="fa fa-fw fa-file-pdf-o text-danger"></i>
                    <a class="text-primary" href="{{ $url }}" target="_blank" title="Click para ver">{{ $escaneo->getNombre() }}</a>
                </td>
                <td width="25%" class="text-right">
                    {{ $escaneo->Archivo->presenter()->getSize('Kb') }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
        <p class="font-size-sm text-muted">El documento no tiene escaneos</p>
    @endif
</div>