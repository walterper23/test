@if( $paginador->total_documentos > 0 )
<a class="btn btn-primary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Página anterior" href="{{ $paginador->pagina_anterior_url }}" @unless($paginador->pagina_anterior) disabled="" @endunless>
    <i class="fa fa-chevron-left fa-fw"></i>
</a>
<a class="btn btn-outline-primary" disabled="">
    <strong>{{ $paginador->pagina_inicio }} - {{ $paginador->pagina_fin }} de {{ $paginador->total_documentos }}</strong>
</a>
<a class="btn btn-primary js-tooltip-enabled" data-toggle="tooltip" title="" href="{{ $paginador->pagina_siguiente_url }}" data-original-title="Página siguiente" @unless($paginador->pagina_siguiente) disabled="" @endunless>
    <i class="fa fa-chevron-right fa-fw"></i>
</a>
@endif