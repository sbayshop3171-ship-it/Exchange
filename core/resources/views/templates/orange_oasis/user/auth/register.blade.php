@extends($activeTemplate . 'layouts.app')
@section('panel')
    @if (gs('registration'))
        @php
            $policyPages = getContent('policy_pages.element', false, null, true);
            $registerContent = getContent('register.content', true);
        @endphp

        <div class="account-section registration">
            <a class="account-section__close" href="{{ route('home') }}"> <i class="las la-times"></i></a>
            <div class="account-wrapper d-flex justify-content-between w-100 flex-wrap flex-lg-nowrap">
                <div class="account-left pe-lg-5">
                    <div class="account-content">
                        <div class="pb-60">
                            <a href="{{ route('home') }}" class="logo">
                                <img src="{{ siteLogo() }}" alt="{{ __(gs('site_name')) }}" title="{{ __(gs('site_name')) }}">
                            </a>
                        </div>
                        <h2 class="title">{{ __(@$registerContent->data_values->heading) }}</h2>
                    </div>
                    <div class="account-thumb pt-80">
                        <img src="{{ frontendImage('register', @$registerContent->data_values->register_image, '800x400') }}" class="mt-auto mw-100">
                    </div>
                </div>
                <div class="account-right my-auto sign-up">
                    <div class="form-wrapper">
                        <div class="mb-4 mb-lg-5">
                            <h3>@lang('Signup Account')</h3>
                        </div>
                        <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha disableSubmission">
                            @csrf
                            <div class="row gy-1">
                                @if (session()->has('reference'))
                                    <div class="col-12">
                                        <div class="floating-label form-group">
                                            <input type="text" placeholder="none" name="referBy" id="referenceBy" class="form-control form--control"
                                                value="{{ session()->get('reference') }}" readonly autofocus autocomplete="off">
                                            <label for="referBy" class="form-label-two fw-semibold">@lang('Reference by')</label>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-sm-6">
                                    <div class="floating-label form-group">
                                        <input type="text" name="firstname" class="form-control form--control" value="{{ old('firstname') }}" required>
                                        <label class="form-label-two fw-semibold" for="">@lang('First Name')</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="floating-label form-group">
                                        <input type="text" name="lastname" class="form-control form--control" value="{{ old('lastname') }}" required>
                                        <label class="form-label-two fw-semibold" for="">@lang('Last Name')</label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="floating-label form-group">
                                        <input type="email" name="email" class="form-control form--control checkUser" value="{{ old('email') }}"
                                            required>
                                        <label class="form-label-two fw-semibold" for="">@lang('Email')</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="floating-label form-group">
                                        <input type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                            name="password" required>
                                        <label class="form-label-two fw-semibold" for="">@lang('Password')</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="floating-label form-group">
                                        <input type="password" name="password_confirmation" class="form-control form--control" required>
                                        <label class="form-label-two fw-semibold" for="">@lang('Confirm Password')</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <x-captcha />
                                    <div class="captchaInput"></div>
                                </div>
                                @if (gs('agree'))
                                    <div class="col-12">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" @checked(old('agree')) name="agree" id="agree"
                                                required>
                                            <label for="agree" class="fw-semibold form-check-label">@lang('I agree with')
                                                @foreach ($policyPages as $policy)
                                                    <a class="text--base" href="{{ route('policy.pages', $policy->slug) }}" target="_blank">
                                                        {{ __($policy->data_values->title) }}
                                                    </a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <button class="btn--base btn  w-100" type="submit">@lang('Signup')</button>
                                </div>

                                @include($activeTemplate . 'partials.social_login')
                            </div>
                            <p class="text-start  mt-3">@lang('Already have account?')
                                <a href="{{ route('user.login') }}" class="text--base">@lang('Sign In')</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="existModalCenter">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                        <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <a href="{{ route('user.login') }}" class="btn btn--base btn-sm">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>

        @if (gs('secure_password'))
            @push('script-lib')
                <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
            @endpush
        @endif

        @push('script')
            <script>
                "use strict";
                (function($) {

                    $('.checkUser').on('focusout', function(e) {
                        var url = '{{ route('user.checkUser') }}';
                        var value = $(this).val();
                        var token = '{{ csrf_token() }}';

                        var data = {
                            email: value,
                            _token: token
                        }

                        $.post(url, data, function(response) {
                            if (response.data != false) {
                                $('#existModalCenter').modal('show');
                            }
                        });
                    });

                    let captcha = $("input[name=captcha]");
                    if (parseInt(captcha.length) > 0) {
                        let html = `
                        <div class="floating-label form-group">
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
                    padding: 0 12px;
                }
            </style>
        @endpush
    @else
        @include($activeTemplate . 'partials.registration_disabled')
    @endif
@endsection
