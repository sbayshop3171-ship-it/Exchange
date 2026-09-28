@php
    $content = getContent('breadcrumb.content', true);
@endphp
<section class="page-header bg_fixed bg_img banner-overlay"
    data-background="{{ frontendImage('breadcrumb', @$content->data_values->background_image, '850x650') }}">
    <div class="container">
        <div class="page-header-content">
            <h2 class="title">{{ $pageTitle }}</h2>
        </div>
    </div>
</section>
