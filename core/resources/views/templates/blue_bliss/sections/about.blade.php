@php
    $aboutContent = getContent('about.content', true);
@endphp
@if (@$aboutContent)
    <section class="about-section padding-top padding-bottom section-bg">
        <div class="container">
            <div class="row flex-wrap-reverse align-items-center">
                <div class="col-lg-6">
                    <div class="section-header left-style margin-olpo text-left">
                        <h2 class="title">{{ __(@$aboutContent->data_values->heading) }}</h2>
                        <p>{{ __(@$aboutContent->data_values->subheading) }}</p>
                    </div>
                    <div class="about-content">
                        @php echo @$aboutContent->data_values->description; @endphp
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-thumb">
                        <img src="{{ frontendImage('about', @$aboutContent->data_values->about_image, '600x400') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
