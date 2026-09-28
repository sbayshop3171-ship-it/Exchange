@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="padding-top padding-bottom section-bg">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper bg-white">
                <div class="verification-area">
                    <h5 class="pb-3 mb-3 text-center border-bottom">@lang('2FA Verification')</h5>
                    <form action="{{ route('user.2fa.verify') }}" method="POST" class="submit-form disableSubmission">
                        @csrf
                        @include($activeTemplate . 'partials.verification_code')
                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        label {
            margin-bottom: 2px;
        }
    </style>
@endpush
