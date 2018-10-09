@push('js-script')
	@if( config('app.debug') )
    {{ Html::script('js/plugins/vue/vue.js') }}
    @else
    {{ Html::script('js/plugins/vue/vue.min.js') }}
    @endif
    {{ Html::script('js/plugins/vue/axios.min.js') }}
@endpush