@if( $paginador->total_documentos > 0 )
<button class="btn btn-primary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Página anterior" @click.prevent="paginar('{{ $paginador->pagina_anterior_url }}')" @unless($paginador->pagina_anterior) disabled="" @endunless>
    <i class="fa fa-chevron-left fa-fw"></i>
</a>
<button class="btn btn-outline-primary" disabled="">
    <strong>{{ $paginador->pagina_inicio }} - {{ $paginador->pagina_fin }} de {{ $paginador->total_documentos }}</strong>
</button>
<button class="btn btn-primary js-tooltip-enabled" data-toggle="tooltip" title="" @click.prevent="paginar('{{ $paginador->pagina_siguiente_url }}')" data-original-title="Página siguiente" @unless($paginador->pagina_siguiente) disabled="" @endunless>
    <i class="fa fa-chevron-right fa-fw"></i>
</button>
@endif