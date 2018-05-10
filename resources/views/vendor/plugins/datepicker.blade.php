@push('css-style')
    {{ Html::style('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}
@endpush

@push('js-script')
    {{ Html::script('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}
    {{ Html::script('js/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}
@endpush