<a href="{{ url( $notificacion -> NOTI_URL ) }}">
	<div class="alert alert-{{ $notificacion -> NOTI_COLOR }} alert-dismissable" role="alert">
	    <button type="button" class="close cerrar-notificacion" data-dismiss="alert" aria-label="Close">
	        <span aria-hidden="true">×</span>
	    </button>
	    <p class="mb-0">
	        <span class="font-size-xs text-muted""><i>{{ $notificacion -> NOTI_CREATED_AT }}</i></span>
	        <i class="fa fa-fw fa-angle-double-right"></i> {{ $notificacion -> NOTI_CONTENIDO }}
	    </p>
	</div>
</a>