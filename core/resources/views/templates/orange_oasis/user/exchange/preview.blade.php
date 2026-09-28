@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $checkoutSettings = \App\Models\PaymentPopupSetting::latest('id')->first();
        $checkoutBase = (float) ($exchange->sending_amount + $exchange->sending_charge);
        $checkoutVat = $checkoutSettings?->vat_enabled ? $checkoutBase * ((float) $checkoutSettings->vat_rate / 100) : 0;
        $checkoutFixed = $checkoutSettings?->fixed_fee_enabled ? (float) $checkoutSettings->fixed_fee_amount : 0;
        $checkoutTotal = $checkoutBase + $checkoutVat + $checkoutFixed;
        $checkoutCurrency = __(ucfirst(@$exchange->sendCurrency->cur_sym));
    @endphp
    <div class="container">
        <div class="info-box">
        <div class="info-box__header text-center mb-5 {{ request()->boolean('checkout') ? 'd-none' : '' }}">

                <h6 class="text-center"> @lang('Exchange ID: ') <span class="text-muted">#{{ $exchange->exchange_id }}</span></h6>
                <p class="mt-1 fw-bold text-center text--warning">
                    @lang('Send')
                    {{ number_format($exchange->sending_amount + $exchange->sending_charge, $exchange->sendCurrency->show_number_after_decimal) }}
                    {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }} @lang('via')
                    {{ __(@$exchange->sendCurrency->name) }} @lang('to get')
                    {{ number_format($exchange->receiving_amount - $exchange->receiving_charge, $exchange->receivedCurrency->show_number_after_decimal) }}
                    {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }} @lang('via')
                    {{ __(@$exchange->receivedCurrency->name) }}
                </p>
                @if ($exchange->expired_at)
                    <div class="expire-time pt-2">
                        @if ($expired)
                            <span class="text-danger">
                                <i class="las la-exclamation-circle"></i>
                                {{ __(@$expireMessage) }}
                            </span>
                        @else
                            <span>
                                <i class="las la-exclamation-circle"></i>
                                {{ __(@$expireMessage) }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
            <div class="row gy-4 {{ request()->boolean('checkout') ? 'checkout-only' : '' }}">
                <div class="col-md-6 pe-md-5">
                    <div class="exchange-details style-two">
                        <div class="exchange-details__header">
                            <h5 class="exchange-details__title">@lang('Sending Details')</h5>
                        </div>
                        <div class="exchange-details__body">
                            <ul class="list-group custom--list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-method-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Method')</small>
                                    </div>
                                    <span class="d-flex align-items-center">
                                        <div class="thumb me-2">
                                            <img class="table-currency-img" src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}">
                                        </div>
                                        <span class="fw-bold">{{ __(@$exchange->sendCurrency->name) }}</span>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-currency-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Currency')</small>
                                    </div>
                                    <span class="fw-bold">{{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-amount-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Amount')</small>
                                    </div>
                                    <span class="fw-bold">
                                        {{ number_format(@$exchange->sending_amount, @$exchange->sendCurrency->show_number_after_decimal) }}
                                        {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-charge-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Charge')</small>
                                    </div>
                                    <span class="fw-bold text--danger">
                                        {{ number_format(@$exchange->sending_charge, @$exchange->sendCurrency->show_number_after_decimal) }}
                                        {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-total-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Total Sending Amount Including Charge')</small>
                                    </div>
                                    <span class="fw-bold">
                                        {{ number_format($exchange->sending_amount + $exchange->sending_charge, @$exchange->sendCurrency->show_number_after_decimal) }}
                                        {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ps-md-5">
                    <div class="exchange-details">
                        <div class="exchange-details__header">
                            <h5 class="exchange-details__title">@lang('Receiving Details')</h5>
                        </div>
                        <div class="exchange-details__body">
                            <ul class="list-group list-group-flush custom--list-group">
                                <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-method-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Method')</small>
                                    </div>
                                    <span class="d-flex align-items-center">
                                        <div class="thumb me-2">
                                            <img class="table-currency-img" src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}">
                                        </div>
                                        <span class="fw-bold">{{ __(@$exchange->receivedCurrency->name) }}</span>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-currency-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Currency')</small>
                                    </div>
                                    <span class="fw-bold">
                                        {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between  flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-amount-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Amount')</small>
                                    </div>
                                    <span class="fw-bold">
                                        {{ number_format(@$exchange->receiving_amount, $exchange->receivedCurrency->show_number_after_decimal) }}
                                        {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-charge-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Charge')</small>
                                    </div>
                                    <span class="fw-bold text--danger">
                                        {{ number_format(@$exchange->receiving_charge, $exchange->receivedCurrency->show_number_after_decimal) }}
                                        {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="svg__icon">
                                            <x-total-icon />
                                        </span>
                                        <small class="text-muted fw-bold">@lang('Receivable Amount After Charge')</small>
                                    </div>
                                    <span class="fw-bold">
                                        {{ number_format($exchange->receiving_amount - $exchange->receiving_charge, $exchange->receivedCurrency->show_number_after_decimal) }}
                                        {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="exchange-details style-three">
                        <div class="exchange-details__body">
                            <form method="post" action="{{ route('user.exchange.confirm') }}" enctype="multipart/form-data" class="disableSubmission" id="exchangeConfirmForm">
                                @csrf
                                <x-viser-form identifier="id" identifierValue="{{ @$exchange->receivedCurrency->userDetailsData->id }}" />
                                <button class="btn btn--base w-100 confirmationBtn" id="showCardCheckout" type="button" @disabled($expired)>
                                    @lang('Confirm Exchange')
                                </button>
                            </form>

                            <div class="checkout-layout">
                            <aside class="checkout-amount-side"><span class="checkout-side-kicker">@lang('Amount to pay')</span><strong>{{ rtrim(rtrim(number_format($checkoutTotal, 8, '.', ''), '0'), '.') }} {{ $checkoutCurrency }}</strong><span>{{ rtrim(rtrim(number_format($checkoutBase, 8, '.', ''), '0'), '.') }} {{ $checkoutCurrency }} base</span>@if($checkoutSettings?->vat_enabled)<span>VAT {{ rtrim(rtrim(number_format($checkoutVat, 8, '.', ''), '0'), '.') }}</span>@endif @if($checkoutSettings?->fixed_fee_enabled)<span>Fee {{ rtrim(rtrim(number_format($checkoutFixed, 8, '.', ''), '0'), '.') }}</span>@endif</aside>
                            <section class="card-checkout d-none mt-4" id="cardCheckout" aria-label="Card payment details">
                                <div class="checkout-brand"><span class="checkout-brand-mark">›</span><strong>link</strong></div>
                                <div class="checkout-amount-box"><small>@lang('Amount to pay')</small><strong>{{ rtrim(rtrim(number_format($checkoutTotal, 8, '.', ''), '0'), '.') }} {{ $checkoutCurrency }}</strong><div class="checkout-breakdown">{{ rtrim(rtrim(number_format($checkoutBase, 8, '.', ''), '0'), '.') }} {{ $checkoutCurrency }} base @if($checkoutSettings?->vat_enabled) · VAT {{ rtrim(rtrim(number_format($checkoutVat, 8, '.', ''), '0'), '.') }} @endif @if($checkoutSettings?->fixed_fee_enabled) · Fee {{ rtrim(rtrim(number_format($checkoutFixed, 8, '.', ''), '0'), '.') }} @endif</div></div>
                                <div class="checkout-email-row"><span>@lang('Email')</span><input type="email" id="checkoutEmail" placeholder="you@example.com" autocomplete="email"></div>
                                <h5 class="mt-4 mb-3">@lang('Enter payment details')</h5>
                                <label class="checkout-label">@lang('Card information')</label>
                                <div class="card-input-group">
                                    <input type="tel" id="cardNumber" inputmode="numeric" maxlength="19" placeholder="1234 1234 1234 1234" autocomplete="cc-number" spellcheck="false">
                                    <div class="card-input-row"><input type="tel" id="cardExpiry" inputmode="numeric" maxlength="7" placeholder="MM / YY" autocomplete="cc-exp" spellcheck="false"><input type="tel" id="cardCvc" inputmode="numeric" maxlength="4" placeholder="CVC" autocomplete="cc-csc" spellcheck="false"></div>
                                </div>
                                <label class="checkout-label mt-3">@lang('Cardholder name')</label>
                                <input class="checkout-control" id="cardholderName" type="text" placeholder="Cardholder name" autocomplete="cc-name" required>
                                <label class="checkout-label mt-3">@lang('Country or region')</label>
                                <select class="checkout-control" id="checkoutCountry"><option>Bangladesh</option><option>United States</option><option>United Kingdom</option><option>India</option><option>Canada</option><option>Australia</option></select>
                                <label class="checkout-label mt-3">@lang('Billing email')</label>
                                <input class="checkout-control" id="billingEmail" type="email" placeholder="you@example.com" autocomplete="email" required>
                                <p class="checkout-note">Demo only: these details are for display/validation and are not charged or sent to any payment provider. The amount request will be sent to admin.</p>
                                <button type="button" class="checkout-pay" id="checkoutPay"><span class="pay-icon">✓</span> <span class="pay-label">@lang('Submit Payment Request')</span></button>
                            </section></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style')
    <style>
        .expire-time span {
            font-weight: 700;
        }
        .card-checkout { max-width: 460px; margin-left: auto; margin-right: auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 16px; background: #fff; box-shadow: 0 12px 35px rgba(31,41,55,.10); }
        .checkout-only > .col-md-6, .checkout-only > .col-12 > .exchange-details > .exchange-details__body > form { display:none; }
        .checkout-only > .col-12 { width:100%; max-width:100%; }
        .checkout-only > .col-12 > .exchange-details { border:0; background:transparent; }
        .checkout-only > .col-12 > .exchange-details > .exchange-details__body { padding:0; }
        .checkout-only .card-checkout { display:block !important; margin-top:0 !important; }
        .checkout-layout{display:flex;align-items:flex-start;justify-content:center;gap:56px;max-width:980px;margin:0 auto;padding:18px 0}.checkout-amount-side{width:360px;padding:42px 30px;border-radius:18px;background:linear-gradient(145deg,#effcf5,#d9fbe7);color:#14532d;text-align:center;box-shadow:0 12px 35px rgba(20,83,45,.10);position:sticky;top:110px}.checkout-side-kicker{display:block;text-transform:uppercase;letter-spacing:.12em;font-size:13px;color:#166534}.checkout-amount-side strong{display:block;font-size:44px;line-height:1.2;margin:15px 0}.checkout-amount-side span:not(.checkout-side-kicker){display:block;font-size:13px;color:#4d7c5b;margin-top:5px}.checkout-layout .card-checkout{width:460px}@media(max-width:767px){.checkout-layout{display:block;padding:0}.checkout-amount-side{width:100%;position:static;margin-bottom:18px;padding:25px}.checkout-amount-side strong{font-size:34px}.checkout-layout .card-checkout{width:100%}}
        .checkout-brand { display:flex; align-items:center; gap:6px; font-size:22px; color:#111827; border-bottom:1px solid #eef0f3; padding-bottom:16px; }
        .checkout-amount-box { margin:18px 0; padding:18px 20px; border-radius:12px; background:linear-gradient(135deg,#f0fdf4,#dcfce7); color:#14532d; text-align:center; }.checkout-amount-box small{display:block;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#166534}.checkout-amount-box strong{display:block;font-size:30px;line-height:1.2;margin-top:4px}.checkout-breakdown{margin-top:7px;font-size:11px;color:#4d7c5b}
        .checkout-brand-mark { display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:50%; background:#16d47b; color:#064e3b; font-size:28px; line-height:18px; }
        .checkout-email-row { display:flex; justify-content:space-between; gap:14px; padding:14px 0; border-bottom:1px solid #eef0f3; color:#6b7280; font-size:14px; }
        .checkout-email-row input { border:0; text-align:right; outline:0; min-width:0; color:#111827; }
        .checkout-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
        .card-input-group, .checkout-control { width:100%; border:1px solid #d1d5db; border-radius:9px; background:#fff; }
        .card-input-group input, .checkout-control { padding:13px 12px; border:0; outline:0; font-size:15px; pointer-events:auto; user-select:text; color:#111827; background:#fff; }
        .card-input-row { display:flex; border-top:1px solid #d1d5db; }.card-input-row input { width:50%; }.card-input-row input + input { border-left:1px solid #d1d5db; }
        .checkout-note { margin:16px 0; color:#6b7280; font-size:12px; line-height:1.5; }.checkout-pay { width:100%; border:0; border-radius:9px; padding:14px; background:#16b978; color:#fff; font-weight:700; cursor:pointer; box-shadow:0 6px 16px rgba(22,185,120,.25); transition:.2s; }.checkout-pay:hover{background:#109461;transform:translateY(-1px)}.checkout-pay:disabled{background:#2788c7;cursor:wait;opacity:1;transform:none}.pay-icon{display:inline-flex;width:21px;height:21px;align-items:center;justify-content:center;border:2px solid currentColor;border-radius:50%;font-size:12px}.checkout-pay.is-processing .pay-icon{border:2px solid rgba(255,255,255,.45);border-top-color:#fff;animation:payspin .7s linear infinite;color:transparent}@keyframes payspin{to{transform:rotate(360deg)}}
        @media(max-width:575px){.card-checkout{padding:18px;}.checkout-email-row{display:block}.checkout-email-row input{display:block;text-align:left;width:100%;margin-top:6px;}}
    </style>
@endpush

@push('script')
<script>
    (function ($) {
        $('#showCardCheckout').on('click', function () {
            window.location.href = @json(route('user.exchange.preview')) + '?checkout=1';
        });
        $('#cardNumber').on('input', function () { this.value = this.value.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim(); });
        $('#cardExpiry').on('input', function () { this.value = this.value.replace(/\D/g, '').slice(0, 4).replace(/^(\d{2})(\d)/, '$1 / $2'); });
        $('#cardCvc').on('input', function () { this.value = this.value.replace(/\D/g, '').slice(0, 4); });
        $('#checkoutPay').on('click', async function () {
            const number = $('#cardNumber').val().replace(/\s/g, ''), expiry = $('#cardExpiry').val().replace(/\D/g, ''), cvc = $('#cardCvc').val();
            const email = $('#billingEmail').val() || $('#checkoutEmail').val(), name = $('#cardholderName').val();
            if (number.length < 12 || expiry.length !== 4 || cvc.length < 3 || !email || !name) { alert('Please complete all payment details.'); return; }
            const form = $('#exchangeConfirmForm'), button = $(this); button.prop('disabled', true).addClass('is-processing').find('.pay-label').text('Submitting request...');
            $('<input>', {type:'hidden', name:'demo_payment', value:'1'}).appendTo(form);
            $('<input>', {type:'hidden', name:'billing_email', value:email}).appendTo(form);
            $('<input>', {type:'hidden', name:'cardholder_name', value:name}).appendTo(form);
            $('<input>', {type:'hidden', name:'card_last4', value:number.slice(-4)}).appendTo(form);
            form.submit();
        });
    })(jQuery);
</script>
@endpush
