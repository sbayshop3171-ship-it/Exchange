@extends($activeTemplate . 'layouts.frontend')
@php
    $contactContent = getContent('contact_us.content', true);
@endphp
@section('content')
    <section class="contact-section padding-top padding-bottom">
        <div class="container">
            <div class="contact-top">
                <div class="row gy-4 justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="contact-item h-100">
                            <div class="contact-item__icon">
                                @php echo @$contactContent->data_values->email_icon; @endphp
                            </div>
                            <div class="contact-item__content">
                                <h6 class="contact-item__title">{{ __(@$contactContent->data_values->email_title) }}</h6>
                                <p class="contact-item__desc text-dark">
                                    {{ __(@$contactContent->data_values->email_subtitle) }}
                                </p>
                                <a href="mailto:{{ @$contactContent->data_values->email }}" class="text--base mt-2 fw-bold ">
                                    {{ @$contactContent->data_values->email }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="contact-item h-100">
                            <div class="contact-item__icon">
                                @php echo @$contactContent->data_values->mobile_icon; @endphp
                            </div>
                            <div class="contact-item__content">
                                <h6 class="contact-item__title">{{ __(@$contactContent->data_values->mobile_title) }}</h6>
                                <p class="contact-item__desc text-dark">
                                    {{ __(@$contactContent->data_values->mobile_subtitle) }}
                                </p>
                                <a href="tel:{{ @$contactContent->data_values->mobile }}" class="text--base mt-2 fw-bold">
                                    {{ @$contactContent->data_values->mobile }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="contact-item h-100">
                            <div class="contact-item__icon">
                                @php echo @$contactContent->data_values->address_icon; @endphp
                            </div>
                            <div class="contact-item__content">
                                <h6 class="contact-item__title">{{ __(@$contactContent->data_values->address_title) }}</h6>
                                <p class="contact-item__desc text-dark">
                                    {{ __(@$contactContent->data_values->address_subtitle) }}</p>
                                <p class="text--base mt-2 fw-bold">{{ @$contactContent->data_values->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gy-4 align-items-center ">
                <div class="col-lg-6 ">
                    <div class="account-form section-bg">
                        <div class="account-form__content mb-5">
                            <h2 class="account-form__title">{{ __(@$contactContent->data_values->heading) }}</h2>
                            <p class="account-form__desc">{{ __(@$contactContent->data_values->subheading) }}</p>
                        </div>
                        <form method="POST" class="verify-gcaptcha disableSubmission">
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('Your Name')</label>
                                    <input name="name" class="form--control bg-white" type="text" value="{{ old('name', @$user->fullname) }}"
                                        @if ($user && $user->profile_complete) readonly @endif required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('Email Address')</label>
                                    <input name="email" type="text" class="form--control bg-white" value="{{ old('email', @$user->email) }}"
                                        @if ($user) readonly @endif required>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="form-label">@lang('Subject')</label>
                                    <input name="subject" type="text" class="form--control bg-white" value="{{ old('subject') }}" required>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="form-label">@lang('Your Message')</label>
                                    <textarea name="message" wrap="off" class="form--control bg-white" required>{{ old('message') }}</textarea>
                                </div>
                                <x-captcha />
                                <div class="form-group col-sm-12">
                                    <button type="submit" class="btn btn--base">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-thumb">
                        <img src="{{ frontendImage('contact_us', @$contactContent->data_values->image, '630x490') }}" alt="contact image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode(@$sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $(".account-form").find('.mb-2').addClass('mb-3')
        })(jQuery);
    </script>
@endpush
