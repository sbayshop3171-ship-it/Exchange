@php
    $registrationDisabled = getContent('register_disable.content', true);
@endphp
<div class="register-disable">
    <div class="container">
        <div class="d-flex flex-column justify-content-center align-items-center gap-3">
            <div class="register-disable-image">
                <img class="fit-image" src="{{ frontendImage('register_disable', @$registrationDisabled->data_values->image, '280x280') }}"
                    alt="registration disable image">
            </div>

            <h3 class="register-disable-title mb-1">
                {{ __(@$registrationDisabled->data_values->heading) }}
            </h3>
            <p class="register-disable-desc mt-0">
                {{ __(@$registrationDisabled->data_values->subheading) }}
            </p>
            <div class="text-center">
                <a href="{{ route('home') }}" class="register-disable-footer-link btn btn--base btn-lg">
                    @lang('Browse') {{ gs('site_name') }}
                </a>
            </div>
        </div>
    </div>
</div>

@push('style')
    <style>
        .register-disable {
            height: 100vh;
            display: flex;
            align-items: center
        }
    </style>
@endpush
