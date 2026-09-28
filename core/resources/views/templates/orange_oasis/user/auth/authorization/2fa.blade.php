@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pb-80 pt-80 bg--light">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper bg-white">
                    <div class="verification-area">
                        <h5 class="pb-3 text-center border-bottom mb-2">@lang('2FA Verification')</h5>
                        <form action="{{ route('user.2fa.verify') }}" method="POST" class="submit-form disableSubmission">
                            @csrf
                            @include($activeTemplate . 'partials.verification_code')
                            <div class="form--group">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .submit-form label {
            margin-top: 1rem;
        }
    </style>
@endpush
