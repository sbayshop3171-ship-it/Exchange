<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    @include('partials.seo')

    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    @stack('style-lib')

    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}">
</head>

@php echo loadExtension('google-analytics') @endphp

<body>

    @if (!request()->routeIs('maintenance'))
        @stack('fbComment')

        @includeWhen(!request()->routeIs('user.login') && !request()->routeIs('user.register'), $activeTemplate . 'partials.notice_bar')

        @include($activeTemplate . 'partials.preloader')
        @include($activeTemplate . 'partials.header')

        @includeWhen(!request()->routeIs('home') && !request()->routeIs('user.login') && !request()->routeIs('user.register'), $activeTemplate . 'partials.breadcrumb')
    @endif

    @yield('content')

    @if (!request()->routeIs('maintenance'))
        @include($activeTemplate . 'partials.footer')
        @include($activeTemplate . 'partials.cookie')
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/plugins.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/swiper.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/nice-select.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script')

    <script>
        "use strict";
        (function($) {
            // language
            $('.langSel').on('click', function(e) {
                let langCode = $(this).data('value');
                window.location.href = "{{ route('home') }}/change/" + langCode;
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });
        })(jQuery);
    </script>
</body>

</html>
