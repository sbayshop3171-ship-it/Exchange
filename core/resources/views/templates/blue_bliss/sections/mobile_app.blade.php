@php
    $mobileAppContent = getContent('mobile_app.content', true);
    $mobileAppElements = getContent('mobile_app.element');
@endphp
@if ($mobileAppContent)
    <div class="exchange-currency padding-top padding-bottom">
        <div class="container">
            <div class="exchange-currency__wrapper section-bg">
                <div class="thumb">
                    <img src="{{ frontendImage('mobile_app', @$mobileAppContent->data_values->mobile_image, '325x490') }}"
                        alt="mobile-image" class="w-unset">
                </div>
                <div class="exchange-currency__content">
                    <h2 class="title">{{ __(@$mobileAppContent->data_values->heading) }}</h2>
                    <p class="desc">{{ __(@$mobileAppContent->data_values->description) }}</p>
                    @foreach (@$mobileAppElements as $mobileAppElement)
                        <a href="{{ $mobileAppElement->data_values->download_link }}" class="exchange-currency__link">
                            <img src="{{ frontendImage('mobile_app', @$mobileAppElement->data_values->app_store_image, '180x55') }}"
                                alt="app-store-image">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
