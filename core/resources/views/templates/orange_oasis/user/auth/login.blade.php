@extends($activeTemplate . 'layouts.app')
@php
    $loginContent = getContent('login.content', true);
@endphp
@section('panel')
    <div class="account-section">
        <a class="account-section__close" href="{{ route('home') }}"> <i class="las la-times"></i></a>
        <div class="account-wrapper  d-flex justify-content-between w-100 flex-wrap flex-md-nowrap">
            <div class="account-left pe-lg-5 pe-md-4">
                <div class="account-content">
                    <div class="pb-60">
                        <a href="{{ route('home') }}" class="logo">
                            <img src="{{ siteLogo() }}" alt="{{ __(gs('site_name')) }}" title="{{ __(gs('site_name')) }}">
                        </a>
                    </div>
                    <h2 class="title">{{ __(@$loginContent->data_values->heading) }}</h2>
                </div>
                <div class="account-thumb pt-80">
                    <img src="{{ frontendImage('login', @$loginContent->data_values->login_image, '800x400') }}" class="mt-auto mw-100">
                </div>
            </div>
            <div class="account-right my-auto">
                <div class="form-wrapper">
                    <div class="mb-4 mb-lg-5">
                        <h3>@lang('Sign in')</h3>
                    </div>
                    <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha disableSubmission">
                        @csrf
                        <div class="floating-label form-group">
                            <input class="form-control form--control" name="username" type="text" required>
                            <label class="form-label-two fw-semibold">@lang('Username Or Email ')</label>
                        </div>
                        <div class="floating-label form-group">
                            <input class="form-control form--control" name="password" type="password" required>
                            <label class="form-label-two fw-semibold">@lang('Password')</label>
                        </div>
                        <x-captcha />
                        <div class="captchaInput"></div>
                        <div class="remember-wrapper d-flex flex-wrap justify-content-between my-3">
                            <div class="form-check">
                                <input type="checkbox" id="remember" class="form-check-input" name="remember">
                                <label for="remember" class="fw-semibold form-check-label">@lang('Remember Me')</label>
                            </div>
                            <p class="text-end"> <a href="{{ route('user.password.request') }}" class="text--base ms-2">@lang('Forgot Password?')</a></p>
                        </div>
                        <button class="btn--base btn  w-100" type="submit">@lang('Login')</button>
                        @include($activeTemplate . 'partials.social_login')
                        <div class="mt-3">
                            <p>
                                @lang("Don't have account?")
                                <a href="{{ route('user.register') }}" class="text--base ms-1">
                                    @lang('Sign Up')
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            let captcha = $("input[name=captcha]");
            if (parseInt(captcha.length) > 0) {
                let html = `
                        <div class="floating-label form-group mb-0">
                                <input type="text" name="captcha" class="form-control form--control" required>
                                <label class="form-label-two fw-semibold" for="">@lang('Captcha')</label>
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
        .content-area {
            z-index: -1;
            height: 100%;
        }

        .row>* {
            padding-right: calc(var(--bs-gutter-x) * .0);
        }

        .other-option {
            margin: 25px 0 25px;
            position: relative;
            text-align: center;
            z-index: 1;
        }

        .other-option::before {
            position: absolute;
            content: "";
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            z-index: -1;
            background: #eee;
        }

        .other-option__text {
            background-color: #fff;
            color: #000;
            display: inline-block;
            padding: 0px 12px;
        }
    </style>
@endpush
