@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pb-80 pt-80 bg--light">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper">
                    <div class="verification-area">
                        <h5 class="pb-3 text-center border-bottom">@lang('Verify Email Address')</h5>
                        <form action="{{ route('user.password.verify.code') }}" method="POST"
                            class="submit-form disableSubmission">
                            @csrf
                            <p class="verification-text">
                                @lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}
                            </p>
                            <input type="hidden" name="email" value="{{ $email }}">
                            @include($activeTemplate . 'partials.verification_code')
                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                            <p class="m-0">
                                @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                <a href="{{ route('user.password.request') }}" class="text--base">@lang('Try to send again')</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .verification-code-wrapper {
            background-color: #fff !important;
        }
    </style>
@endpush
