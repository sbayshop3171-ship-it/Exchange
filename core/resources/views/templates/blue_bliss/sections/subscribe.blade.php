@php
    $subscriptionContent = getContent('subscribe.content', true);
@endphp

<section class="newsletter-section padding-top padding-bottom bg_img bg_fixed bg_overlay"
    data-background="{{ frontendImage('subscribe', @$subscriptionContent->data_values->background_image, '1200x360') }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-header mb-5 text-white">
                    <h2 class="title text-white">{{ __(@$subscriptionContent->data_values->heading) }}</h2>
                    <p class="text-white">{{ __(@$subscriptionContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <form class="newsletter-form disableSubmission" method="POST" id="newsletr-form">
                    @csrf
                    <input type="email" placeholder="@lang('Enter Your Email...')" name="email" class="form--control text--base" required>
                    <button type="submit" id="subscribe" class="h5 text-white">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        "use strict";
        (function($) {
            $("#newsletr-form").on('submit', function(e) {
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
