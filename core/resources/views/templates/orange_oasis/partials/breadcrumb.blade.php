@php
    $breadcrumbContent = getContent('breadcrumb.content', true);
@endphp

<section class="inner-banner bg_img"
    data-background="{{ frontendImage('breadcrumb', @$breadcrumbContent->data_values->background_image, '850x650') }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6 text-center">
                <h2 class="title text-white">{{ __($pageTitle) }}</h2>
            </div>
        </div>
    </div>
</section>
