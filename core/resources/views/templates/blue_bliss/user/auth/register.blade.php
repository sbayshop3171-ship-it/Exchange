@extends($activeTemplate . 'layouts.frontend')
@if (gs('registration'))
    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $registerContent = getContent('register.content', true);
    @endphp
    @section('content')
        <section class="padding-top padding-bottom section-bg">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-7 col-xl-6">
                        <div class="card custom--card">
                            <div class="card-body">
                                <h4 class="form__title mb-5">{{ __(@$registerContent->data_values->heading) }}</h4>
                                <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha disableSubmission">
                                    @csrf
                                    <div class="row">
                                        @if (session()->get('reference') != null)
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="referenceBy" class="form-label">@lang('Reference by')</label>
                                                    <input type="text" name="referBy" id="referenceBy" class="form-control form--control"
                                                        value="{{ session()->get('reference') }}" readonly>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('First Name')</label>
                                                <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Last Name')</label>
                                                <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">@lang('E-Mail Address')</label>
                                                <input type="email" class="form-control form--control checkUser" name="email"
                                                    value="{{ old('email') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Password')</label>
                                                <input type="password"
                                                    class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                                    name="password" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Confirm Password')</label>
                                                <input type="password" class="form-control form--control" name="password_confirmation" required>
                                            </div>
                                        </div>
                                    </div>
                                    <x-captcha />
                                    @if (gs('agree'))
                                        <div class="form-group form--check">
                                            <input class="form-check-input" type="checkbox" id="agree" @checked(old('agree')) name="agree"
                                                required>
                                            <label for="agree">@lang('I agree with')
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
                                    @endif
                                    <div class="form-group">
                                        <button type="submit" id="recaptcha" class="btn btn--base w-100">@lang('Register')</button>
                                    </div>

                                    @include($activeTemplate . 'partials.social_login')

                                    <p class="mt-3">
                                        @lang('Already have an account?')
                                        <a class="text--base" href="{{ route('user.login') }}">
                                            @lang('Login')
                                        </a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
                        <h6 class="text-center">@lang('You already have an account please Login')</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <a href="{{ route('user.login') }}" class="btn btn-sm btn--base">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection

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
            })(jQuery);
        </script>
    @endpush

    @push('style')
        <style>
            .content-area {
                z-index: -1;
                height: 100%;
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
@else
    @section('content')
        @include($activeTemplate . 'partials.registration_disabled')
    @endsection
@endif
