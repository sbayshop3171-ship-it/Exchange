@php
    $bannerContent = getContent('banner.content', true);
@endphp
@if (@$bannerContent)
    <section class="banner-section bg_fixed bg_img banner-overlay"
             data-background="{{ frontendImage('banner', @$bannerContent->data_values->image, '1200x685') }}">
        <div class="container">
            <div class="banner-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="content">
                            <h2 class="title text-white">{{ __(@$bannerContent->data_values->heading) }}</h2>
                        </div>
                        <div class="currency-converter">
                            @include($activeTemplate . 'partials.exchange_form')
                            <div class="card custom--card best-rate-slide d-none mt-4 border-0 shadow-none">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-column align-items-start">
                                        <ul class="best-rate-list w-100 justify-content-center"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="exchange-form-bottom">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <form id="tracking-form" action="{{ route('exchange.tracking') }}" method="GET"
                                  class="disableSubmission">
                                @csrf
                                <div class="exchange-form-bottom">
                                    <div class="form-group">
                                        <input type="text" name="exchange_id" class="form--control"
                                               placeholder="@lang('Enter your exchange ID')" id="exchang">
                                    </div>
                                    <div class="exchange-form-bottom__btn">
                                        <button type="submit" class="btn--base btn">@lang('Track now')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal trackModal" id="trackModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-center"> @lang('Exchange Information')</h4>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div id="exchange-information"></div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            "use strict";
            (function($) {
                $('#tracking-form').on('submit', function(e) {
                    e.preventDefault();
                    let exchangeId = $(this).find("input[name=exchange_id]").val() || null;
                    $.ajax({
                        url: $(this).attr('action'),
                        type: "GET",
                        dataType: 'json',
                        data: {
                            "exchange_id": exchangeId
                        },
                        cache: false,
                        success: function(response) {
                            if (response.success) {
                                $('#trackModal').find("#exchange-information").html(response.html)
                                $("#trackModal").modal('show');
                            } else {
                                notify('error', response.error || response.message)
                            }
                        },
                        error: function() {
                            notify('error', `@lang('Something went the wrong')`)
                        }
                    });
                });
            })(jQuery);
        </script>
    @endpush
@endif



@push('style')
    <style>
        @media screen and (max-width:1199px) {
            .form-group span {
                font-size: 13px !important;
            }
        }
    </style>
@endpush
