@php
    $registrationDisabled = getContent('register_disable.content', true);
@endphp
<div class="register-disable padding-top padding-bottom section-bg">
    <div class="container text-center">
        <div class="register-disable-image mb-5">
            <img class="fit-image" src="{{ frontendImage('register_disable', @$registrationDisabled->data_values->image, '280x280') }}"
                alt="register disable image">
        </div>
        <h4 class="register-disable-title mb-2">
            {{ __(@$registrationDisabled->data_values->heading) }}
        </h4>
        <p class="register-disable-desc">
            {{ __(@$registrationDisabled->data_values->subheading) }}
        </p>
    </div>
</div>

@push('style')
    <style>
        .fit-image{
            width: unset;
        }
    </style>
@endpush
