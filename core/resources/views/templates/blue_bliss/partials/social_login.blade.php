@php
    $credentials = gs('socialite_credentials');
@endphp
@if (
    @$credentials->google->status == Status::ENABLE ||
        @$credentials->facebook->status == Status::ENABLE ||
        @$credentials->linkedin->status == Status::ENABLE)

    <div class="col-12">
        <div class="other-option">
            <span class="other-option__text">@lang('OR')</span>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-3">

        @if ($credentials->facebook->status == Status::ENABLE)
            <a href="{{ route('user.social.login', 'facebook') }}" class="btn btn-outline-facebook btn-sm flex-grow-1">
                <span class="me-1"><i class="fab fa-facebook-f"></i></span>@lang('Facebook')
            </a>
        @endif

        @if ($credentials->google->status == Status::ENABLE)
            <a href="{{ route('user.social.login', 'google') }}" class="btn btn-outline-google btn-sm flex-grow-1">
                <span class="me-1"><i class="lab la-google"></i></span>@lang('Google')
            </a>
        @endif

        @if ($credentials->linkedin->status == Status::ENABLE)
            <a href="{{ route('user.social.login', 'linkedin') }}" class="btn btn-outline-linkedin btn-sm flex-grow-1">
                <span class="me-1"><i class="lab la-linkedin-in"></i></span>@lang('Linkedin')
            </a>
        @endif
    </div>
@endif
