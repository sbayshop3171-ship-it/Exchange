@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center gy-4">
            @if (!$user->ts)
                <div class="col-md-6">
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Add Your Account')</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-3">
                                @lang('Use the QR code or setup key on your Google Authenticator app to add your account. ')
                            </h6>
                            <div class="form-group mx-auto text-center w-50">
                                <img class="mx-auto" src="{{ $qrCodeUrl }}" alt="QR">
                            </div>
                            <div class="form-group">
                                <label class="form-label">@lang('Setup Key')</label>
                                <div class="input-group br-0">
                                    <input type="text" name="key" value="{{ $secret }}"
                                        class="form-control form--control referralURL" readonly>
                                    <button type="button" class="input-group-text bg--base text-white" id="copyBoard">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <label class="mb-2 "><i class="fa fa-info-circle text--base"></i> @lang('Help')</label>
                            <p class="m-0">
                                @lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.') <a
                                    href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                                    class="text--base" target="_blank">
                                    @lang('Download')
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-6">
                @if ($user->ts)
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Disable 2FA Security')</h5>
                        </div>
                        <form action="{{ route('user.twofactor.disable') }}" method="POST" class="disableSubmission">
                            <div class="card-body">
                                @csrf
                                <input type="hidden" name="key" value="{{ $secret }}">
                                <div class="form-group">
                                    <label class="form-label">@lang('Google Authenticator OTP')</label>
                                    <input type="text" class="form-control form--control" name="code" required>
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Enable 2FA Security')</h5>
                        </div>
                        <form action="{{ route('user.twofactor.enable') }}" method="POST" class="disableSubmission">
                            <div class="card-body">
                                @csrf
                                <input type="hidden" name="key" value="{{ $secret }}">
                                <div class="form-group">
                                    <label class="form-label">@lang('Google Authenticator OTP')</label>
                                    <input type="text" class="form-control form--control" name="code" required>
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .copied::after {
            background-color: #{{ gs('base_color') }};
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#copyBoard').on('click', function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush
