@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="padding-top padding-bottom section-bg">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <h5 class="pb-3 text-center border-bottom">@lang('Verify Email Address')</h5>
                    <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form disableSubmission">
                        @csrf
                        <p class="verification-text mt-2">
                            @lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}
                        </p>
                        <input type="hidden" name="email" value="{{ $email }}">

                        @include($activeTemplate . 'partials.verification_code')

                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                        <div>
                            @lang('Please check including your Junk/Spam folder. if not found, you can')
                            <a href="{{ route('user.password.request') }}" class="text--base">
                                @lang('Try to send again')
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .verification-code-wrapper {
            background-color: #ffffff !important;
        }
    </style>
@endpush
