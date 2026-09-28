@php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonialElements = getContent('testimonial.element');
@endphp
<div class="pt-80 pb-80 section-bg">
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-md-12">
                <div class="section-title">
                    <div class="section-title__wrapper">
                        <h2 class="section-title__title mb-1">
                            {{ __(@$testimonialContent->data_values->heading) }}
                        </h2>
                        <p class="section-title__desc">
                            {{ __(@$testimonialContent->data_values->subheading) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="testimonial-slider">
            @foreach (@$testimonialElements as $testimonialElement)
                <div class="single-slide">
                    <div class="testimonial-item">
                        <div class="testimonial-item__thumb">
                            <img src="{{ frontendImage('testimonial', @$testimonialElement->data_values->client_image, '90x90') }}">
                        </div>
                        <div class="testimonial-item__content">
                            <h5 class="testimonial-item__content-name mb-2">
                                {{ __(@$testimonialElement->data_values->name) }}
                            </h5>
                            <p class="testimonial-item__content-designation mb-2">
                                {{ __(@$testimonialElement->data_values->designation) }}</p>
                            <p class="testimonial-item__content-text">
                                {{ __(@$testimonialElement->data_values->description) }}
                            </p>
                            <ul class="rating d-flex justify-content-center mt-3 gap-1">
                                @php $rating=@$testimonialElement->data_values->rating; @endphp
                                @for ($i = 0; $i < 5; $i++)
                                    @if ($i < $rating)
                                        <li class="text--base"><i class="las la-star"></i></li>
                                    @else
                                        <li>
                                            <i class="las la-star"></i>
                                        </li>
                                    @endif
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
