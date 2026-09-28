@php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonialElements = getContent('testimonial.element');
@endphp
<section class="client-section padding-bottom padding-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="section-header left-style">
                    <h2 class="title">{{ __(@$testimonialContent->data_values->heading) }}</h2>
                    <p>{{ __(@$testimonialContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="client-slider">
            <div class="swiper-wrapper">
                @foreach ($testimonialElements as $testimonialElement)
                    <div class="swiper-slide">
                        <div class="client-item">
                            <div class="client-thumb">
                                <div class="content">
                                    <h6 class="title">{{ __(@$testimonialElement->data_values->name) }}</h6>
                                    <span>{{ __(@$testimonialElement->data_values->designation) }}</span>
                                </div>
                            </div>
                            <div class="client-content">
                                <blockquote> {{ __(@$testimonialElement->data_values->description) }}</blockquote>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="common-pagination"></div>
    </div>
</section>
