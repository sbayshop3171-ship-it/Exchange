@php
    $paymentGateways = \App\Models\PaymentPopupGateway::active()->get();
    $hasPendingUnlock = auth()->check() && auth()->user()->exchangeUnlocks()->where('status', 'pending')->exists();
@endphp

<form class="exchange-form disableSubmission" method="POST" action="{{ route('exchange.start') }}" id="exchange-form">
    @csrf
    <div class="form-group sendData">
        <div class="input-wrapper">
            <input type="number" step="any" name="sending_amount" id="sending_amount" class="form--control"
                   placeholder="@lang('You Send')" value="{{ old('sending_amount') }}" required>
            <select required class="select2 form-control form--control" data-type="select" name="sending_currency"
                    id="send">
                <option value="" selected disabled>@lang('Select One')</option>
                @foreach ($sellCurrencies as $sellCurrency)
                    <option
                            data-image="{{ getImage(getFilePath('currency') . '/' . @$sellCurrency->image, getFileSize('currency')) }}"
                            data-min="{{ $sellCurrency->minimum_limit_for_buy }}"
                            data-max="{{ $sellCurrency->maximum_limit_for_buy }}"
                            data-buy="{{ $sellCurrency->buy_at }}" data-currency="{{ @$sellCurrency->cur_sym }}"
                            data-show_number="{{ @$sellCurrency->show_number_after_decimal }}"
                            value="{{ $sellCurrency->id }}" data-select-for="send" @selected(old('sending_currency') == $sellCurrency->id)>
                        {{ __($sellCurrency->name) }} - {{ __($sellCurrency->cur_sym) }}
                    </option>
                @endforeach
            </select>
        </div>
        <span class="d-none" id="currency-limit"></span>
    </div>
    <span class="exchange-form__icon">
        <i class="las la-exchange-alt"></i>
    </span>
    <div class="form-group receiveData ">
        <div class="input-wrapper">
            <input type="number" step="any" name="receiving_amount" class="form--control" id="receiving_amount"
                   value="{{ old('receiving_amount') }}" placeholder="@lang('You Get')" required>
            <select class="select2 form-control form--control" name="receiving_currency" id="receive" required
                    value.bind="selectedThing2">


                <option value="" selected disabled>@lang('Select One')</option>
                @foreach ($buyCurrencies as $buyCurrency)
                    <option
                            data-image="{{ getImage(getFilePath('currency') . '/' . @$buyCurrency->image, getFileSize('currency')) }}"
                            data-sell="{{ $buyCurrency->sell_at }}"
                            data-currency="{{ @$buyCurrency->cur_sym }}"
                            data-min="{{ $buyCurrency->minimum_limit_for_sell }}"
                            data-max="{{ $buyCurrency->maximum_limit_for_sell }}"
                            data-reserve="{{ $buyCurrency->reserve }}" value="{{ $buyCurrency->id }}"
                            data-show_number="{{ @$buyCurrency->show_number_after_decimal }}"
                            data-select-for="received" @selected(old('receiving_currency') == $buyCurrency->id)>
                        {{ __($buyCurrency->name) }} - {{ __($buyCurrency->cur_sym) }}
                    </option>
                @endforeach
            </select>
        </div>
        <span class="d-none" id="currency-limit-received"></span>
    </div>
    <div class="exchange-btn">
        <button type="submit" class="btn--base btn">@lang('Exchange')</button>
    </div>
</form>

@if(auth()->check() && !auth()->user()->is_exchange_unlocked)
    <div class="modal fade payment-unlock-modal" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h4 class="modal-title mb-1">@lang('Verification Gate')</h4>
                        <p class="mb-0 text-muted">
                            @if($hasPendingUnlock)
                                @lang('Your payment verification is under review.')
                            @else
                                @lang('Complete payment verification to unlock exchange access.')
                            @endif
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4 pt-3">
                    @if($hasPendingUnlock)
                        <div class="alert alert-warning mb-3">
                            @lang('Your payment verification is under review.')
                        </div>
                    @endif
                    <form action="{{ route('user.payment.popup.unlock') }}" method="POST" enctype="multipart/form-data" class="disableSubmission" id="paymentUnlockForm">
                        @csrf
                        <input type="hidden" name="gateway_id" id="paymentGatewayId" value="">
                        <input type="hidden" name="method" id="paymentMethodName" value="">
                        <input type="hidden" name="paid_by_user" id="paidByUser" value="0">

                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="gateway-tab" data-bs-toggle="tab" data-bs-target="#gateway-tab-pane" type="button" role="tab">@lang('Gateway')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="proof-tab" data-bs-toggle="tab" data-bs-target="#proof-tab-pane" type="button" role="tab">@lang('Upload Proof')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab">@lang('Payment Details')</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="gateway-tab-pane" role="tabpanel">
                                @if($paymentGateways->count())
                                    <div class="row g-3">
                                        @foreach($paymentGateways as $gateway)
                                            <div class="col-md-6">
                                                <button type="button" class="payment-gateway-option w-100 text-start border rounded p-3 bg-light" data-gateway-id="{{ $gateway->id }}" data-gateway-name="{{ $gateway->name }}" data-wallet="{{ $gateway->wallet_address }}" data-network="{{ $gateway->network }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ __($gateway->name) }}</strong>
                                                            <div class="text-muted small">{{ __($gateway->symbol) }}{{ $gateway->network ? ' • ' . __($gateway->network) : '' }}</div>
                                                        </div>
                                                        <span class="badge bg-success">@lang('Select')</span>
                                                    </div>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0">@lang('No payment gateways are available right now. Please try again later.')</div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="proof-tab-pane" role="tabpanel">
                                <div class="form-group">
                                    <label>@lang('Upload Payment Proof')</label>
                                    <input type="file" name="payment_proof" class="form-control" accept="image/*">
                                </div>
                                <div class="mt-3">
                                    <label>@lang('Notes')</label>
                                    <textarea name="notes" rows="3" class="form-control" placeholder="@lang('Optional notes for admin verification')"></textarea>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="details-tab-pane" role="tabpanel">
                                <div class="border rounded p-3 bg-light mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="fw-bold">@lang('Wallet Address')</span>
                                        <button type="button" class="btn btn-sm btn-outline--primary copy-wallet-btn">@lang('Copy')</button>
                                    </div>
                                    <div id="walletAddressText" class="text-break">@lang('Choose a gateway to display wallet details')</div>
                                </div>
                                <div class="form-group">
                                    <label>@lang('I Paid')</label>
                                    <input type="checkbox" name="is_paid" value="1" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" data-width="100%" data-onstyle="-success" data-offstyle="-danger">
                                </div>
                                <div class="form-group mt-3">
                                    <label>@lang('Transaction ID / TrxID')</label>
                                    <input type="text" name="trx_id" class="form-control" placeholder="@lang('Enter payment transaction ID')">
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" class="btn btn--secondary me-2" data-bs-dismiss="modal">@lang('Cancel')</button>
                            <button type="submit" class="btn btn--base">@lang('Submit Verification')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
            let sendId, sendMinAmount, sendMaxAmount, sendAmount, sendCurrency, sendCurrencyBuyRate;
            let receivedId, receivedAmount, receivedCurrency, receiveCurrencySellRate, sendShowNumber, receivingShowNumber;

            //=============change select2 structure
            $('.select2').select2({
                templateResult: formatState
            });

            function formatState(state) {
                if (!state.id) return state.text;
                let selectType = $(state.element).data('select-for').toUpperCase();
                if (sendId && selectType == 'RECEIVED' && sendId == state.element.value) {
                    return false;
                }
                if (receivedId && selectType == 'SEND' && receivedId == state.element.value) {
                    return false;
                }
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

                $('#currency-limit').html(
                    `@lang('You Send') <span class="text--base">${sendMinAmount.toFixed(sendShowNumber)}</span> - <span class="text--base">${sendMaxAmount.toFixed(sendShowNumber)}</span> ${sendCurrency}`
                );
                $('#currency-limit').removeClass('d-none').addClass("d-block mt-2");

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
                let reserveAmount = parseFloat($(this).find(':selected').data('reserve'));
                receivingShowNumber = $(this).find(':selected').data('show_number');

                $('#currency-limit-received').html(
                    `@lang('Select One')
                    <span class="text--base">${minAmount.toFixed(receivingShowNumber)}</span> - <span class="text--base">${maxAmount.toFixed(receivingShowNumber)}</span>
                    ${receivedCurrency} | Reserve <span class="text--base">${reserveAmount.toFixed(receivingShowNumber)}</span> ${receivedCurrency}`
                );

                $('#currency-limit-received').removeClass('d-none').addClass("d-block mt-2");
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

            $('#exchange-form').on('submit', function(e) {
                const isUnlocked = {{ auth()->check() && auth()->user()->is_exchange_unlocked ? 'true' : 'false' }};

                if (!isUnlocked) {
                    e.preventDefault();

                    $.ajax({
                        url: '{{ route('user.payment.popup.gateways') }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const gateways = response && Array.isArray(response.gateways) ? response.gateways : [];
                            const modalBody = $('#paymentModal .modal-body');
                            const list = gateways.length ? gateways.map(function(gateway) {
                                return `
                                    <div class="col-md-6">
                                        <button type="button" class="payment-gateway-option w-100 text-start border rounded p-3 bg-light" data-gateway-id="${gateway.id}" data-gateway-name="${gateway.name}" data-wallet="${(gateway.wallet_address || '').replace(/"/g, '&quot;')}" data-network="${(gateway.network || '').replace(/"/g, '&quot;')}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>${gateway.name}</strong>
                                                    <div class="text-muted small">${gateway.symbol}${gateway.network ? ' • ' + gateway.network : ''}</div>
                                                </div>
                                                <span class="badge bg-success">Select</span>
                                            </div>
                                        </button>
                                    </div>
                                `;
                            }).join('') : '<div class="alert alert-warning mb-0">No payment gateways are available right now. Please try again later.</div>';

                            modalBody.find('#gateway-tab-pane .row').html(list);
                            if (modalBody.find('#gateway-tab-pane .alert').length) {
                                modalBody.find('#gateway-tab-pane .alert').removeClass('d-none');
                            }

                            @if($hasPendingUnlock)
                                $('#paymentModal .alert-warning').removeClass('d-none');
                            @endif
                            $('#paymentModal').modal('show');
                        },
                        error: function() {
                            $('#paymentModal').modal('show');
                        }
                    });

                    return false;
                }
            });

            // The gateway list is replaced after the popup opens via AJAX. Bind this
            // handler to a stable ancestor so both initial and dynamically-rendered
            // gateway buttons remain usable on every theme/layout.
            $(document).off('click.paymentGateway', '#paymentModal .payment-gateway-option')
                .on('click.paymentGateway', '#paymentModal .payment-gateway-option', function(e) {
                e.preventDefault();
                const gatewayId = $(this).data('gateway-id');
                const gatewayName = $(this).data('gateway-name');
                const wallet = $(this).data('wallet');
                const network = $(this).data('network');

                $('.payment-gateway-option').removeClass('active');
                $(this).addClass('active');

                $('#paymentGatewayId').val(gatewayId);
                $('#paymentMethodName').val(gatewayName);
                $('#paidByUser').val(0);
                $('#walletAddressText').text(wallet || '@lang('No wallet address available')');
                $('#walletAddressText').find('.network-info').remove();
                $('#paymentModal .nav-tabs .nav-link').removeClass('active');
                $('#gateway-tab').addClass('active');
                $('#paymentModal .tab-pane').removeClass('show active');
                $('#gateway-tab-pane').addClass('show active');

                if (wallet) {
                    $('.copy-wallet-btn').data('wallet', wallet);
                }

                if (network) {
                    $('#walletAddressText').append('<div class="network-info small text-muted mt-2">Network: ' + network + '</div>');
                }
            });

            $('input[name="is_paid"]').on('change', function() {
                $('#paidByUser').val($(this).is(':checked') ? 1 : 0);
            });

            $('.copy-wallet-btn').on('click', function() {
                const value = $(this).data('wallet') || $('#walletAddressText').text();
                navigator.clipboard.writeText(value).then(function () {
                    notify('success', '@lang('Copied to clipboard')');
                }).catch(function () {
                    notify('error', '@lang('Copy failed')');
                });
            });

            $('#sending_amount').on('input', function(e) {
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

            $('#receiving_amount').on('input', function(e) {
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
                let amountReceived = (sendCurrencyBuyRate / receiveCurrencySellRate) * sendAmount;
                $("#receiving_amount").val(amountReceived.toFixed(receivingShowNumber));
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
            width: 100%;
        }

        .select2-search--dropdown {
            display: block !important;
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

        .select2-container .selection {
            width: 220px;
            height: 48px;
            -moz-border-radius: 0;
            border-radius: 0;
            position: absolute;
            right: 4px;
            top: 50%;
            padding: 0 10px;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            border-radius: 2px;
            background: #E8E8E8;
            border: 0 !important;
        }

        @media (max-width:1199px) {
            .select2-container .selection {
                width: 170px;
            }
        }

        @media (max-width:991px) {
            .select2-container .selection {
                width: 296px;
            }
        }

        @media (max-width:767px) {
            .select2-container .selection {
                width: 235px;
            }
        }

        @media (max-width:575px) {
            .select2-container .selection {
                width: 215px;
            }
        }

        @media (max-width:424px) {
            .select2-container .selection {
                transform: unset;
                position: relative;
                width: 100%;
                right: 0;
            }
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            top: 80%;
        }

        .select2-container--default .select2-results__option--disabled {
            display: none;
        }

        .select2-dropdown {
            border: 1px solid #aaaaaa2e;
        }

        img.currency-image {
            width: 25px;
            height: 25px;
            margin-right: 8px;
        }

        .select2-container--default .select2-selection--single {
            border: 0;
            background-color: transparent;
        }

        .payment-gateway-option {
            transition: all 0.2s ease;
        }

        .payment-gateway-option:hover,
        .payment-gateway-option.active {
            border-color: #3d4ae3 !important;
            background: rgba(61, 74, 227, 0.05) !important;
        }

        .select2-results__option:empty {
            display: none !important;
        }


        .select2-container--default .select2-selection--single .select2-selection__arrow:after {
            top: 4px !important;
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
