@php
    $subscriptionContent = getContent('subscribe.content', true);
@endphp

<section class="newsletter-section pt-60 pb-60 bg_img subscribe bg_overlay"
    data-background="{{ frontendImage('subscribe', @$subscriptionContent->data_values->background_image, '1200x360') }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-title text-white text-center">
                    <h2 class="section-title__title text-white">
                        {{ __(@$subscriptionContent->data_values->heading) }}
                    </h2>
                    <p class="section-title__desc">{{ __(@$subscriptionContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <form class="newsletter-form disableSubmission" method="POST" id="newsletter-form">
                    @csrf
                    <input type="email" placeholder="@lang('Enter Your Email....')" name="email" class="form--control text--base" required>
                    <button type="submit" id="subscribe" class="h5 text-white">
                        @lang('Subscribe')
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('style')
    <style>
        .newsletter-form input:-webkit-autofill {
            -webkit-text-fill-color: #fff !important;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $("#newsletter-form").on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($(this)[0]);
                $.ajax({
                    url: `{{ route('subscribe') }}`,
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        if (resp.success) {
                            $('input[name="email"]').val('');
                            notify('success', resp.message)
                        } else {
                            notify('error', resp.error || `@lang('Something went wrong')`)
                        }
                    },
                    error: function(e) {
                        notify(`@lang('Something went wrong')`)
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
