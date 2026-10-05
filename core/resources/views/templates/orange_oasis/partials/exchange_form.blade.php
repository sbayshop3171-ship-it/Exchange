@php
    $paymentGateways = \App\Models\PaymentPopupGateway::active()->get();
    $paymentSettings = \App\Models\PaymentPopupSetting::latest('id')->first();
    $subscriptionPlans = \App\Models\PaymentSubscriptionPlan::active()->orderBy('duration_days')->get();
    $hasPendingUnlock = auth()->check() && auth()->user()->exchangeUnlocks()->where('status', 'pending')->exists();
@endphp

<div class="custom-widget mb-4">
    <form action="{{ route('exchange.start') }}" method="POST" id="exchange-form" class="disableSubmission">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <h6 class="banner__widget-title mb-3 mt-0">@lang('You Send')</h6>
                <div class="form-group mb-3">
                    <div class="select-item">
                        <select required class="select2 form-control form--control" data-type="select"
                                name="sending_currency" id="send">
                            <option value="" selected disabled>@lang('Select One')</option>
                            @foreach ($sellCurrencies as $sellCurrency)
                                <option
                                        data-image="{{ getImage(getFilePath('currency') . '/' . @$sellCurrency->image, getFileSize('currency')) }}"
                                        data-min="{{ $sellCurrency->minimum_limit_for_buy }}"
                                        data-max="{{ $sellCurrency->maximum_limit_for_buy }}"
                                        data-buy="{{ $sellCurrency->buy_at }}"
                                        data-show_number="{{ @$sellCurrency->show_number_after_decimal }}"
                                        data-currency="{{ @$sellCurrency->cur_sym }}" value="{{ $sellCurrency->id }}"
                                        data-select-for="send" @selected(old('sending_currency') == $sellCurrency->id)>
                                    {{ __($sellCurrency->name) }} - {{ __($sellCurrency->cur_sym) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="" class="form-label fw-medium">@lang('Send Amount')</label>
                    <div class="input-group">
                        <input type="number" step="any" class="form-control form--control rounded"
                               name="sending_amount" id="sending_amount" value="{{ old('sending_amount') }}"
                               placeholder="0.00">
                        <span class="input-group-text d-none bg--base text-white border-0"></span>
                    </div>
                </div>
                <div class="rate--txt d-none">
                    <div>
                        <span>@lang('Limit:')</span>
                        <span class="limit-exchange">
                            <span class="text--base"></span>
                            <span class="currency_name"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3 mt-0">@lang('You Get')</h6>
                <div class="form-group mb-3" id="receiving-currency-wrapper">
                    <div class="select-item ">
                        <select class="select2 form-control form--control" name="receiving_currency" id="receive"
                                required value.bind="selectedThing2">
                            <option value="" selected disabled>@lang('Select One')</option>
                            @foreach ($buyCurrencies as $buyCurrency)
                                <option
                                        data-image="{{ getImage(getFilePath('currency') . '/' . @$buyCurrency->image, getFileSize('currency')) }}"
                                        data-sell="{{ $buyCurrency->sell_at }}"
                                        data-currency="{{ @$buyCurrency->cur_sym }}"
                                        data-min="{{ $buyCurrency->minimum_limit_for_sell }}"
                                        data-max="{{ $buyCurrency->maximum_limit_for_sell }}"
                                        data-reserve="{{ $buyCurrency->reserve }}"
                                        data-show_number="{{ @$buyCurrency->show_number_after_decimal }}"
                                        value="{{ $buyCurrency->id }}" data-select-for="received"
                                        @selected(old('receiving_currency') == $buyCurrency->id)>
                                    {{ __($buyCurrency->name) }} - {{ __($buyCurrency->cur_sym) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="" class="form-label fw-medium">@lang('Get Amount')</label>
                    <div class="input-group">
                        <input type="number" step="any" class="form-control form--control rounded"
                               id="receiving_amount" name="receiving_amount" value="{{ old('receiving_amount') }}"
                               placeholder="0.00">
                        <span class="input-group-text d-none bg--base text-white border-0"></span>
                    </div>
                </div>
                <div class="rate--txt-received d-none">
                    <div>
                        <span>@lang('Limit:')</span>
                        <span class="limit-received-exchange">
                            <span class="text--base"></span>
                            <span class="currency_name"></span>
                        </span>
                        <span>@lang('| Reserve:')</span>
                        <span class="reserve-amount">
                            <span class="text--base"></span>
                            <span class="currency_name"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button class="btn btn--base mt-2 w-100" type="submit">
                    <span class="me-2"> <i class="las la-exchange-alt"></i></span>@lang('Exchange Now')
                </button>
            </div>
            <div class="card custom--card best-rate-slide d-none mt-3 border-0 shadow-none">
                <div class="card-body p-0">
                    <div class="d-flex flex-column align-items-start">
                        <ul class="best-rate-list w-100 justify-content-center"></ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@if(auth()->check() && !auth()->user()->hasExchangeAccess())
<div class="modal fade" id="paymentPopupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content">
        <div class="modal-header d-block text-center"><button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button><h5 class="modal-title">@lang('Payment Verification')</h5><div id="paymentAmountBadge" class="mt-3 px-4 py-2 rounded-pill d-inline-block text-white shadow-sm" style="background:linear-gradient(135deg,#ff7138,#e94f1d);font-weight:700;letter-spacing:.2px;">@lang('Please Pay:') <span id="paymentAmountValue">{{ rtrim(rtrim(number_format((float)($paymentSettings?->unlock_fee_amount ?? 0), 8, '.', ''), '0'), '.') }}</span> <span id="paymentAmountCurrency">{{ $paymentSettings?->fee_currency ?? gs('cur_text') }}</span></div></div>
        <form action="{{ route('user.payment.popup.unlock') }}" method="POST" enctype="multipart/form-data" id="paymentUnlockForm">
            @csrf
            <div class="modal-body">
                @if($hasPendingUnlock)<div class="alert alert-warning">@lang('Your payment verification is under review.')</div>@endif
                <input type="hidden" name="gateway_id" id="paymentGatewayId">
                <input type="hidden" name="subscription_plan_id" id="subscriptionPlanId">
                <input type="hidden" name="method" value="I Paid">
                @if($hasPendingUnlock)<fieldset disabled>@endif
                <div class="row g-3">
                @forelse($paymentGateways as $gateway)
                    <div class="col-md-6"><button type="button" class="payment-gateway-option w-100 text-start border rounded p-3 bg-light" data-gateway-id="{{ $gateway->id }}" data-wallet="{{ $gateway->wallet_address }}" data-qr="{{ $gateway->qr_code_image ? getImage(getFilePath('verify').'/'.$gateway->qr_code_image) : '' }}" data-trx-required="{{ $gateway->is_trx_required || $paymentSettings?->is_trx_required ? 1 : 0 }}" data-proof-required="{{ $gateway->is_proof_required || $paymentSettings?->is_proof_required ? 1 : 0 }}" data-fee-amount="{{ $gateway->unlock_fee_amount ?? $paymentSettings?->unlock_fee_amount ?? 0 }}" data-fee-currency="{{ $gateway->fee_currency ?: ($paymentSettings?->fee_currency ?? gs('cur_text')) }}"><strong>{{ __($gateway->name) }}</strong> <span class="text-muted">{{ __($gateway->symbol) }} {{ $gateway->network ? '• '.$gateway->network : '' }}</span></button></div>
                @empty
                    <div class="col-12"><div class="alert alert-warning">@lang('No payment gateways are available right now.')</div></div>
                @endforelse
                </div>
                @if($subscriptionPlans->isNotEmpty())
                    <div class="mt-4 d-none" id="subscriptionPlanChoices">
                        <h6 class="mb-2">@lang('Subscription Plan') <small class="text-muted">(@lang('Optional'))</small></h6>
                        <div class="row g-2">
                            <div class="col-md-6"><label class="subscription-plan-option border rounded p-3 d-block h-100"><input type="radio" name="subscription_plan_choice" value="" checked> <strong>@lang('Standard gateway payment')</strong><div class="small text-muted">@lang('Use the gateway default amount')</div></label></div>
                            @foreach($subscriptionPlans as $plan)
                                <div class="col-md-6"><label class="subscription-plan-option border rounded p-3 d-block h-100"><input type="radio" name="subscription_plan_choice" value="{{ $plan->id }}" data-amount="{{ $plan->amount }}" data-currency="{{ $plan->currency }}"> <strong>{{ $plan->name }}</strong><div class="small text-muted">{{ $plan->duration_days }} @lang('day(s)') · {{ rtrim(rtrim(number_format((float) $plan->amount, 8, '.', ''), '0'), '.') }} {{ $plan->currency }}</div></label></div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div id="paymentGatewayDetails" class="border rounded p-3 mt-3 d-none text-center"><div id="paymentQrCode" class="mb-2"></div><div class="text-break"><strong>@lang('Wallet Address'):</strong> <span id="paymentWalletAddress"></span></div></div>
                <div class="mt-3 {{ !$paymentSettings?->is_trx_required ? 'd-none' : '' }}" id="trxField"><label>@lang('Transaction ID / TrxID')</label><input type="text" name="trx_id" class="form-control" @required($paymentSettings?->is_trx_required)></div>
                <div class="mt-3 {{ !$paymentSettings?->is_proof_required ? 'd-none' : '' }}" id="proofField"><label>@lang('Upload Payment Proof')</label><input type="file" name="payment_proof" class="form-control" accept="image/*" @required($paymentSettings?->is_proof_required)></div>
                <div class="mt-3"><label>@lang('Notes')</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
                <div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="is_paid" value="1" id="isPaid" required><label class="form-check-label" for="isPaid">@lang('I Paid')</label></div>
                @if($hasPendingUnlock)</fieldset>@endif
            </div>
            <div class="modal-footer"><button type="button" class="btn btn--secondary" data-bs-dismiss="modal">@lang('Close')</button><button type="submit" class="btn btn--base" @disabled($hasPendingUnlock)>@if($hasPendingUnlock) @lang('Pending Approval') @else @lang('Submit Verification') @endif</button></div>
        </form>
    </div></div>
</div>
@endif

@push('style-lib')
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('#exchange-form').on('submit', function(e) {
                @if(auth()->check() && !auth()->user()->hasExchangeAccess())
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    $('#paymentPopupModal').modal('show');
                    return false;
                @endif
            });

            $(document).on('click', '.payment-gateway-option', function() {
                $('#paymentGatewayId').val($(this).data('gateway-id'));
                const fee = parseFloat($(this).data('fee-amount')) || 0;
                $('#paymentAmountBadge').data({defaultFee: fee, defaultCurrency: $(this).data('fee-currency') || ''});
                $('#paymentAmountValue').text(fee.toLocaleString(undefined, {maximumFractionDigits: 8}));
                $('#paymentAmountCurrency').text($(this).data('fee-currency') || '');
                $('#subscriptionPlanChoices').removeClass('d-none');
                $('input[name="subscription_plan_choice"][value=""]').prop('checked', true);
                $('#subscriptionPlanId').val('');
                $('#paymentWalletAddress').text($(this).data('wallet') || '-');
                const qr = $(this).data('qr');
                $('#paymentQrCode').html(qr ? '<img src="' + qr + '" class="img-fluid mx-auto d-block" style="max-height:180px">' : '');
                $('#trxField').toggleClass('d-none', !$(this).data('trx-required'));
                $('#proofField').toggleClass('d-none', !$(this).data('proof-required'));
                $('#trxField input').prop('required', !!$(this).data('trx-required'));
                $('#proofField input').prop('required', !!$(this).data('proof-required'));
                $('#paymentGatewayDetails').removeClass('d-none');
                $('.payment-gateway-option').removeClass('border-primary');
                $(this).addClass('border-primary');
            });
            $(document).on('change', 'input[name="subscription_plan_choice"]', function() {
                const planId = $(this).val();
                $('#subscriptionPlanId').val(planId);
                if (planId) {
                    $('#paymentAmountValue').text(parseFloat($(this).data('amount')).toLocaleString(undefined, {maximumFractionDigits: 8}));
                    $('#paymentAmountCurrency').text($(this).data('currency'));
                } else {
                    $('#paymentAmountValue').text(parseFloat($('#paymentAmountBadge').data('defaultFee') || 0).toLocaleString(undefined, {maximumFractionDigits: 8}));
                    $('#paymentAmountCurrency').text($('#paymentAmountBadge').data('defaultCurrency') || '');
                }
            });
            let sendId, sendMinAmount, sendMaxAmount, sendAmount, sendCurrency, sendCurrencyBuyRate;
            let receivedId, receivedAmount, receivedCurrency, receiveCurrencySellRate, sendShowNumber, receivingShowNumber;

            $('.select2').select2({
                templateResult: formatState
            });

            function formatState(state) {
                if (!state.id) return state.text;
                let selectType = $(state.element).data('select-for').toUpperCase();

                if (sendId && selectType == 'RECEIVED' && sendId == state.element.value) return false;
                if (receivedId && selectType == 'SEND' && receivedId == state.element.value) return false;

                return $('<img class="ms-1"   src="' + $(state.element).data('image') + '"/> <span class="ms-3">' +
                    state.text + '</span>');
            }

            $(document).ready(function() {
                let selectedSendId = null;
                let selectedReceiveId = null;

                $('[name=sending_currency]').on('change', function() {
                    selectedSendId = $(this).val();
                    selectedReceiveId = $('[name=receiving_currency]').val();

                    if (selectedSendId && selectedReceiveId) {
                        fetchBestRates(selectedSendId, selectedReceiveId);
                    } else {
                        $(".best-rate-slide").addClass("d-none").removeClass("show");
                    }
                });

                $('[name=receiving_currency]').on('change', function() {
                    selectedReceiveId = $(this).val();
                    if (selectedSendId && selectedReceiveId) {
                        fetchBestRates(selectedSendId, selectedReceiveId);
                    } else {
                        $(".best-rate-slide").addClass("d-none").removeClass("show");
                    }
                });

                function fetchBestRates(sendId, receiveId) {
                    $.ajax({
                        url: `{{ route('exchange.best.rates') }}`,
                        type: "GET",
                        data: {
                            sending_currency: sendId,
                            receiving_currency: receiveId
                        },
                        beforeSend: function() {
                            $(".best-rate-list").html(
                                '<li class="list-group-item text-center">Loading...</li>');
                            $(".best-rate-slide").removeClass("d-none").addClass("show");
                        },
                        success: function(response) {
                            if (response.rates && response.rates.length > 0) {
                                updateBestRatesUI(response.rates);
                            } else {
                                $(".best-rate-list").html(
                                    '<li class="list-group-item text-warning text-center">No rates available</li>'
                                );
                                $(".best-rate-slide").removeClass("show").addClass("d-none");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching best rates:", error);
                            $(".best-rate-list").html(
                                '<li class="list-group-item text-danger text-center">Failed to load rates</li>'
                            );
                            $(".best-rate-slide").removeClass("show").addClass("d-none");
                        }
                    });
                }

                function updateBestRatesUI(rates) {
                    let rateList = $(".best-rate-list");
                    rateList.empty();

                    if (rates.length === 0) {
                        rateList.html(
                            '<li class="list-group-item text-warning text-center">No rates available</li>');
                        return;
                    }

                    rates.forEach(rate => {
                        let rateValue = parseFloat(rate.rate);
                        let listItem = `
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-600">
                                    <span>1</span> ${rate.sending_currency}-${rate.send_currency_symbol}  =  
                                    ${isNaN(rateValue) || rateValue <= 0 ? '<span class="text-danger">N/A</span>' : `<span class="text--base">${rateValue.toFixed(rate.receive_show_number)}</span>`} 
                                    ${rate.receiving_currency}-${rate.receive_currency_symbol}
                                </span>
                            </li>
                        `;
                        rateList.append(listItem);
                    });
                }

            });

            @if (old('sending_currency'))
                sendAmount = "{{ old('sending_amount') }}";
                sendAmount = parseFloat(sendAmount);
                $("#sending_amount").val(sendAmount.toFixed("{{ gs('show_number_after_decimal') }}"));
                setTimeout(() => {
                    $('#send').trigger('change');
                });
            @endif

            @if (old('receiving_currency'))
                receivedAmount = "{{ old('receiving_amount') }}";
                receivedAmount = parseFloat(receivedAmount);
                $("#receiving_amount").val(receivedAmount.toFixed("{{ gs('show_number_after_decimal') }}"));
                setTimeout(() => {
                    $('#receive').trigger('change');
                });
            @endif

            $('[name=sending_currency]').on('change', function(e) {
                sendId = parseInt($(this).val());
                sendMinAmount = parseFloat($(this).find(':selected').data('min'));
                sendMaxAmount = parseFloat($(this).find(':selected').data('max'));
                sendCurrency = $(this).find(':selected').data('currency');
                sendCurrencyBuyRate = $(this).find(':selected').data('buy');
                sendShowNumber = $(this).find(':selected').data('show_number');

                console.log(sendMinAmount, sendShowNumber);


                $('.limit-exchange').find('.text--base').text(
                    `${sendMinAmount.toFixed(sendShowNumber)}- ${sendMaxAmount.toFixed(sendShowNumber)}`);
                $('.limit-exchange').find('.currency_name').text(sendCurrency);
                $('.rate--txt').removeClass('d-none');

                $("#sending_amount").siblings('.input-group-text').removeClass('d-none');
                $("#sending_amount").removeClass('rounded');
                $("#sending_amount").siblings('.input-group-text').text(sendCurrency);

                if (sendId) {
                    $(this).closest('.form-group').find('.select2-selection__rendered').html(
                        `<img src="${$(this).find(':selected').data('image')}" class="currency-image"/> ${$(this).find(':selected').text()}`
                    )
                    calculationReceivedAmount();
                }
            });

            $('[name=receiving_currency]').on('change', function(e) {
                receivedId = parseInt($(this).val());
                receiveCurrencySellRate = $(this).find(':selected').data('sell');
                receivedCurrency = $(this).find(':selected').data('currency');

                let minAmount = parseFloat($(this).find(':selected').data('min'));
                let maxAmount = parseFloat($(this).find(':selected').data('max'));
                let reserveAmount = parseFloat($(this).find(':selected').data('reserve'))
                receivingShowNumber = $(this).find(':selected').data('show_number');

                $('.limit-received-exchange').find('.text--base').text(
                    `${minAmount.toFixed(receivingShowNumber)} - ${maxAmount.toFixed(receivingShowNumber)}`);
                $('.reserve-amount').find('.text--base').text(`${reserveAmount.toFixed(receivingShowNumber)}`);
                $('.limit-received-exchange').find('.currency_name').text(receivedCurrency);
                $('.reserve-amount').find('.currency_name').text(receivedCurrency);
                $('.rate--txt-received').removeClass('d-none');

                $("#receiving_amount").siblings('.input-group-text').removeClass('d-none');
                $("#receiving_amount").removeClass('rounded');
                $("#receiving_amount").siblings('.input-group-text').text(receivedCurrency);

                if (receivedId) {
                    $(this).closest('.form-group').find('.select2-selection__rendered').html(
                        `<img src="${$(this).find(':selected').data('image')}" class="currency-image"/> ${$(this).find(':selected').text()}`
                    )
                    calculationReceivedAmount();
                }
            });

            $('#exchange-form').on('input', '#sending_amount', function(e) {
                sendAmount = parseFloat(this.value);
                if (sendAmount < 0) {
                    sendAmount = 0;
                    notify('error', 'Negative amount is not allowed');
                    $(this).val('');
                    $('input[name="receiving_amount"]').val('');
                } else {
                    calculationReceivedAmount();
                }
            });

            $('#exchange-form').on('input', '#receiving_amount', function(e) {
                receivedAmount = parseFloat(this.value);
                if (receivedAmount < 0) {
                    notify('error', 'Negative amount is not allowed');
                    receivedAmount = 0;
                    $(this).val('');
                    $('input[name="sending_amount"]').val('');
                } else {
                    calculationSendAmount();
                }
            });

            const calculationReceivedAmount = () => {
                if (!sendId && !receivedId && !sendCurrencyBuyRate && !receiveCurrencySellRate) {
                    return false;
                }
                let amountReceived = sendCurrencyBuyRate / receiveCurrencySellRate * sendAmount;
                $("#receiving_amount").val(parseFloat(amountReceived).toFixed(receivingShowNumber));
            }

            const calculationSendAmount = () => {
                if (!sendId && !receivedId && !sendCurrencyBuyRate && !receiveCurrencySellRate) {
                    return false;
                }
                let amountReceived = (receiveCurrencySellRate / sendCurrencyBuyRate) * receivedAmount;
                $("#sending_amount").val(amountReceived.toFixed(sendShowNumber));
            }
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .select2-container .select2-selection--single {
            height: 46px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
        }

        .select2-container--default img {
            width: 28px;
            height: 28px;
            object-fit: contain;
        }

        .select2-results__option--selectable {
            display: flex;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            top: 80%;
        }

        img.currency-image {
            width: 25px;
            height: 25px;
            margin-right: 8px;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid hsl(var(--border));
        }

        .select2-results__option:empty {
            display: none !important;
        }

        .best-rate-slide {
            transition: all 0.3s ease-in-out;
            opacity: 0;
            transform: translateY(10px);
            display: none;
        }

        .best-rate-slide.show {
            opacity: 1;
            transform: translateY(0);
            display: block;
        }

        .best-rate-item {
            cursor: pointer;
        }

        /* style best rate list design  */

        .best-rate-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 14px;
        }

        .best-rate-list .list-group-item {
            position: relative;
            font-size: 0.875rem;
            background: #f2f2f2;
            padding: 7px 13px;
            border-radius: 5px;
        }

        .fw-600 {
            font-weight: 600;
        }
    </style>
@endpush
