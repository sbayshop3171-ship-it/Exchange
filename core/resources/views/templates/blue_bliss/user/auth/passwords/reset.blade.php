@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="padding-top padding-bottom section-bg">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-4">
                <div class="card custom--card">
                    <div class="card-body">
                        <p>@lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')</p>
                        <form method="POST" action="{{ route('user.password.update') }}" class="disableSubmission">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Password')</label>
                                <input type="password"
                                    class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                    name="password" required>
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
    </div>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
