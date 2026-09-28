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
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    @stack('style-lib')

    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}">
</head>

@php echo loadExtension('google-analytics') @endphp

<body>
    @stack('fbComment')

    <div class="preloader">
        <div class="preloader__img">
            <img src="{{ siteFavicon() }}" alt="image">
        </div>
    </div>

    @if (!request()->routeIs('user.login') && !request()->routeIs('user.register') && !request()->routeIs('maintenance'))
        @include($activeTemplate . 'partials.notice_bar')
    @endif

    @yield('panel')

    @if (!request()->routeIs('user.login') && !request()->routeIs('user.register'))
        @include($activeTemplate . 'partials.footer')
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
 
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script')

    <script>
        (function($) {
            "use strict";
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

      

            $('.showFilterBtn').on('click', function() {
                $('.responsive-filter-card').slideToggle();
            });

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

            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        colum.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });

            $('.captcha div').css({
                "background-color": "#fff",
                "border": "1px solid #f1f1f1",
                "border-radius": "5px",
                "font-size": "24px",
                "letter-spacing": "16px",
            });
        })(jQuery);
    </script>
</body>

</html>
