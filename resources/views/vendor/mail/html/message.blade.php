@component('vendor.mail.html.layout')
    {{-- Header --}}
    @slot('header')
        @component('vendor.mail.html.header', ['url' => config('app.url')])
            {{ title( config_var('Sistema.Nombre') ) }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('vendor.mail.html.subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('vendor.mail.html.footer')
            &copy; {{ date('Y') }} {{ title( config_var('Sistema.Nombre') ) }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
