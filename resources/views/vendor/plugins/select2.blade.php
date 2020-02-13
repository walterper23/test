@push('css-style')
    {{ Html::style('js/plugins/select2/select2.min.css') }}
    {{ Html::style('js/plugins/select2/select2-bootstrap.min.css') }}
@endpush

@push('js-script')
    {{ Html::script('js/plugins/select2/select2.full.min.js') }}
@endpush