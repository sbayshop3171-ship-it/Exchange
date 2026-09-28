@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="padding-top padding-bottom section-bg">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-4">
                <div class="card custom--card">
                    <div class="card-body">
                        <p>@lang('To recover your account please provide your email or username to find your account.')</p>
                        <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha disableSubmission">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">@lang('Email or Username')</label>
                                <input type="text" class="form-control form--control" name="value" value="{{ old('value') }}" autofocus="off"
                                    required>
                            </div>
                            <x-captcha />
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
