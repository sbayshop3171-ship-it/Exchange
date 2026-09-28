@php
    $faqContent = getContent('faq.content', true);
    $faqElements = getContent('faq.element');
@endphp
@if ($faqContent)
    <section class="faq-section padding-top padding-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 d-lg-block d-none rtl">
                    <img src="{{ frontendImage('faq', @$faqContent->data_values->faq_image, '600x600') }}"
                        alt="faq image">
                </div>
                <div class="col-lg-7">
                    <div class="section-header left-style">
                        <h2 class="title">{{ __(@$faqContent->data_values->heading) }}</h2>
                        <p>{{ __(@$faqContent->data_values->subheading) }}</p>
                    </div>
                    <div class="faq-wrapper mb--20">
                        @foreach ($faqElements as $faqElement)
                            <div class="faq-item {{ $loop->first == 1 ? 'active open' : '' }}">
                                <div class="faq-title">
                                    <h6 class="title">{{ __(@$faqElement->data_values->question) }}</h6>
                                    <span class="right-icon"></span>
                                </div>
                                <div class="faq-content">{{ __($faqElement->data_values->answer) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
