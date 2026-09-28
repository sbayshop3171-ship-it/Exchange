@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pb-80 pt-80 bg--light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-5">
                    <div class="custom-widget">
                        <div class="mb-3">
                            <p>@lang('To recover your account please provide your email or username to find your account.')</p>
                        </div>
                        <form method="POST" action="{{ route('user.password.email') }}"
                              class="verify-gcaptcha disableSubmission">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">@lang('Email or Username')</label>
                                <input type="text" class="form-control form--control" name="value" value="{{ old('value') }}" required autofocus="off">
                            </div>
                            <x-captcha />
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
