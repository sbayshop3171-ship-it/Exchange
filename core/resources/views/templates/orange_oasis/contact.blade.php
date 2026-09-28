@extends($activeTemplate . 'layouts.frontend')
@php
    $contact = getContent('contact_us.content', true);
    $elements = getContent('contact_us.element', orderById: true);
@endphp
@section('content')
    @if ($contact)
        <div class="pt-80 pb-80">
            <div class="container">
                <div class="row flex-wrap-reverse gy-5">
                    <div class="col-lg-6">
                        <div class="d-flex flex-column gap-5 pe-xl-5">
                            @foreach ($elements as $element)
                                <div class="contact-info">
                                    <div class="info-item d-flex gap-3 fs--18px">
                                        <div class="info-item__icon text--base">
                                            <img src="{{ getImage('assets/images/frontend/contact_us/' . @$element->data_values->icon, '60x60') }}"
                                                 alt="icon">
                                        </div>
                                        <div class="info-item__content">
                                            <h6 class="info-item__title mb-2 fw-600">
                                                {{ __($element->data_values->heading) }}</h6>
                                            <p>{{ __($element->data_values->subheading) }}</p>
                                            <span class="fw-medium">{{ __($element->data_values->value) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="contact-form form-wrapper">
                            <h3 class="mb-2">{{ __(@$contact->data_values->heading) }}</h3>
                            <p class="mb-3 mb-sm-4 fs--18px">{{ __(@$contact->data_values->subheading) }}</p>
                            <form action="{{ route('contact') }}" method="POST" class="verify-gcaptcha">
                                @csrf
                                <div class="floating-label mb-4">
                                    <input type="text" name="name" class="floating-input form-control form--control"
                                           placeholder="none" required>
                                    <label class="form-label-two required" for="username">@lang('Name')</label>
                                </div>

                                <div class="floating-label mb-4">
                                    <input type="email" name="email" class="floating-input form-control form--control"
                                           placeholder="none" required>
                                    <label class="form-label-two required" for="username">@lang('Email')</label>
                                </div>

                                <div class="floating-label mb-4">
                                    <input type="text" name="subject" class="floating-input form-control form--control" placeholder="none" required>
                                    <label class="form-label-two required" for="username">@lang('Subject')</label>
                                </div>

                                <div class="floating-label mb-4">
                                    <textarea class="floating-input form-control form--control" name="message" required placeholder="@lang('Write Message')"></textarea>
                                    <label class="form-label-two required" for="username">@lang('Message')</label>
                                </div>
                                <x-captcha />
                                <button type="submit" class="btn btn--base w-100 fs--18px">@lang('Submit')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="map-wrapper">
            <iframe class="map" src="{{ @$contact->data_values->map_url }}" width="100%" height="450"
                    style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    @endsection
@endif

@push('script')
    <script>
        "use strict";
        (function($) {

            let captcha = $("input[name=captcha]");
            if (parseInt(captcha.length) > 0) {
                let html = `
                        <div class="floating-label form-group mb-0">
                                <input type="text" name="captcha" class="floating-input form-control form--control" placeholder="none" required>
                                <label class="form-label-two" for="">@lang('Captcha')</label>
                        </div>
                        `;
                $(captcha).remove();
                $(".captchaInput").html(html);
            }

            $('.customCaptcha').find('label').first().remove();

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .contact-form {
            max-width: unset;
        }

        .fw-medium {
            font-weight: 600 !important;
        }

        .contact-info {
            background: #ffffff;
            padding: 30px;
            box-shadow: 0 3px 45px #e6edf4db;
            border-radius: 15px;
        }
    </style>
@endpush
