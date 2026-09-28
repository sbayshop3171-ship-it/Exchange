@php
    $bannerContent = getContent('banner.content', true);
@endphp

<div class="banner-section pt-80 pb-80">
    <div class="banner-bg">
        <img src="{{ frontendImage('banner', @$bannerContent->data_values->background_image, '960x330') }}" class="h-100 w-100">
    </div>
    <div class="container">
        <h2 class="banner-title text-white"> {{ __(@$bannerContent->data_values->heading) }} </h2>
        <div class="row g-4">
            <div class="col-lg-8">
                @include($activeTemplate . 'partials.exchange_form')
                @include($activeTemplate . 'partials.latest_exchange')
            </div>
            <div class="col-lg-4">
                @include($activeTemplate . 'partials.tracking_form')
                @include($activeTemplate . 'partials.exchange_rate')
                @include($activeTemplate . 'partials.reserve_currencies')
            </div>
        </div>
    </div>
</div>
