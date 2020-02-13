@if( $paginador->total_documentos > 0 )
<a href="{{ $paginador->pagina_anterior ? $paginador->pagina_anterior_url : '#' }}" class="btn btn-primary" title="Página anterior">
    <i class="fa fa-chevron-left fa-fw"></i>
</a>
<button type="button" class="btn btn-outline-primary" disabled="">
    <strong>{{ $paginador->pagina_inicio }} - {{ $paginador->pagina_fin }} de {{ $paginador->total_documentos }}</strong>
</button>
<a href="{{ $paginador->pagina_siguiente ? $paginador->pagina_siguiente_url : '#' }}" class="btn btn-primary" title="Página siguiente">
    <i class="fa fa-chevron-right fa-fw"></i>
</a>
@endif