@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pb-80 pt-80 bg--light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="custom-widget">
                        <div class="mb-3">
                            <p>@lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')</p>
                        </div>
                        <form method="POST" action="{{ route('user.password.update') }}" class="disableSubmission">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Password')</label>
                                <input class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                       type="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <input type="password" class="form-control form--control" name="password_confirmation"
                                       required>
                            </div>
                            <button type="submit" class="btn btn--base w-100"> @lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('style')
    <style>
        .hover-input-popup .input-popup {
            bottom: 75% !important;
        }
    </style>
@endpush
