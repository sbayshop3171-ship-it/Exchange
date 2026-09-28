@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row gy-4">
                    <div class="col-md-12 d-flex gap-2 justify-content-end align-items-center flex-wrap">
                        @if ($exchange->status == Status::EXCHANGE_INITIAL)
                            <a href="{{ route('user.exchange.complete', $exchange->id) }}" class="btn btn--info btn-sm"> <i
                                   class="fas fa-money-check-alt"></i> @lang('Complete Exchange')</a>
                        @else
                            @if ($exchange->status == Status::EXCHANGE_APPROVED)
                                <button class="repeat-btn btn btn--base-outline btn-sm"
                                        data-send-currency-id="{{ @$exchange->sendCurrency->id }}"
                                        data-send-currency="{{ @$exchange->sendCurrency }}"
                                        data-received-currency-id="{{ @$exchange->receivedCurrency->id }}"
                                        data-received-currency="{{ @$exchange->receivedCurrency }}"
                                        data-sending-amount="{{ $exchange->sending_amount }}"
                                        data-receiving-amount="{{ $exchange->receiving_amount }}"
                                        data-send-image="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}"
                                        data-received-image="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}">
                                    <i class="fas fa-redo-alt"></i>
                                    @lang('Repeat Exchange')
                                </button>
                            @endif
                            <a href="{{ route('user.exchange.invoice', ['id' => $exchange->exchange_id, 'type' => 'download']) }}"
                               class="btn btn--info btn-sm"> <i class="fa fa-download"></i> @lang('Download')</a>
                            <a href="{{ route('user.exchange.invoice', ['id' => $exchange->exchange_id, 'type' => 'print']) }}"
                               class="btn btn--success  btn-sm"> <i class="fa fa-print"></i> @lang('Print')</a>
                        @endif
                        <a href="{{ route('user.exchange.list', 'list') }}" class="btn btn--dark  btn-sm">
                            <i class="la la-undo"></i> @lang('Back')
                        </a>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom--card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Sending Details')</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush p-3">
                                    <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="svg__icon">
                                                <x-method-icon />
                                            </span>
                                            <small class="fw-bold">@lang('Method')</small>
                                        </div>
                                        <span class="d-flex align-items-center">
                                            <div class="thumb me-2">
                                                <img class="currency__image"
                                                     src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}"
                                                     alt="currency image">
                                            </div>
                                            <span class="fw-bold">{{ __(@$exchange->sendCurrency->name) }}</span>
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="svg__icon">
                                                <x-currency-icon />
                                            </span>
                                            <small class="fw-bold">@lang('Currency')</small>
                                        </div>
                                        <span class="fw-bold">{{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="svg__icon">
                                                <x-amount-icon />
                                            </span>
                                            <small class="fw-bold">@lang('Amount')</small>
                                        </div>
                                        <span class="fw-bold">
                                            {{ number_format(@$exchange->sending_amount, $exchange->sendCurrency->show_number_after_decimal) }}
                                            {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                                        </span>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="svg__icon">
                                                <x-charge-icon />
                                            </span>
                                            <small class="fw-bold">@lang('Charge')</small>
                                        </div>
                                        <span class="fw-bold text--danger">
                                            {{ number_format(@$exchange->sending_charge, $exchange->sendCurrency->show_number_after_decimal) }}
                                            {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="svg__icon">
                                                <x-total-icon />
                                            </span>
                                            <small class="fw-bold">@lang('Total Sending Amount Including Charge')</small>
                                        </div>
                                        <span class="fw-bold">
                                            {{ number_format($exchange->sending_amount + $exchange->sending_charge, $exchange->sendCurrency->show_number_after_decimal) }}
                                            {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card custom--card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Receiving Details')</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush p-3">
                                    <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="svg__icon">
                                                <x-method-icon />
                                            </span>
                                            <small class="fw-bold">@lang('Method')</small>
                                        </div>
                                        <span class="d-flex align-items-center">
                                            <div class="thumb me-2">
                                                <img class="currency__image"
                                                     src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}">
                                            </div>
                                            <span class="fw-bold">{{ __(@$exchange->receivedCurrency->name) }}</span>
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="svg__icon">
                                                <x-currency-icon />
                                            </span>
                                            <small class="fw-bold">@lang('Currency')</small>
                                        </div>
                                        <span class="fw-bold">
                                            {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap border-dotted">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="svg__icon">
                                                <x-amount-icon />
                                            </span>
                                            <small class="fw-bold">@lang('Amount')</small>
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
                                            <small class="fw-bold">@lang('Charge')</small>
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
                                            <small class="fw-bold">@lang('You\'ll Receive Amount After Charged')</small>
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
                        <div class="card custom--card">
                            <div class="card-header">
                                <h5 class="card-title">@lang('Exchange Information')</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush p-3">
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <span>@lang('Exchange ID:')</span>
                                        <span class="fw-bold">{{ __($exchange->exchange_id) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap  align-items-center">
                                        <span>@lang('Your ') <span class="text--base">{{ __(@$exchange->receivedCurrency->name) }}</span>
                                            @lang('Wallet ID/Number')</span>
                                        <span class="fw-bold">{{ $exchange->wallet_id }}</span>
                                    </li>
                                    @if ($exchange->status == Status::EXCHANGE_APPROVED)
                                        <li class="list-group-item d-flex justify-content-between flex-wrap align-items-center">
                                            <span>@lang('Admin Transaction/Wallet Number')</span>
                                            <span class="fw-bold">{{ $exchange->admin_trx_no }}</span>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between flex-wrap align-items-center">
                                        <span>@lang('Status')</span>
                                        <span class="text-end">@php echo $exchange->badgeData() @endphp</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap align-items-center">
                                        <span>@lang('Exchange Date')</span>
                                        <div class="text-end">
                                            <span class="d-block">{{ showDateTime($exchange->created_at) }}</span>
                                            <span>{{ diffForHumans($exchange->created_at) }}</span>
                                        </div>
                                    </li>
                                    @if ($exchange->admin_feedback != null)
                                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                                            @if ($exchange->status == Status::EXCHANGE_CANCEL)
                                                <span class="text--danger">@lang('Failed Reason')</span>
                                            @else
                                                <span>@lang('Admin Feedback')</span>
                                            @endif
                                            <span class="text-end">{{ __($exchange->admin_feedback) }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionModalLabel">@lang('Repeat Transaction')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('exchange.start') }}" method="POST" id="exchange-form"
                          class="disableSubmission">
                        @csrf
                        <input type="hidden" name="sending_currency" class="sending_currency" value="">
                        <input type="hidden" name="receiving_currency" class="receiving_currency" value="">
                        <div class="row align-items-center mb-2">
                            <div class="col-5">
                                <div class="exchange-info text-center">
                                    <div class="exchange-info__thumb">
                                        <img src="" class="send-image" alt="">
                                    </div>
                                    <div class="exchange-info__title">
                                        <span class="send_currency_name_text"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <div class="exchange-info__icon">
                                    <i class="las la-arrow-right"></i>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="exchange-info text-center">
                                    <div class="exchange-info__thumb">
                                        <img src="" class="received-image" alt="">
                                    </div>
                                    <div class="exchange-info__title">
                                        <span class="buy_currency_name_text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group mb-0 form-design">
                                    <label for="" class="form-label fw-bold mb-0">@lang('You are sending')</label>
                                    <div class="input-group align-items-center">
                                        <input type="number" step="any"
                                               class="form-control form--control rounded" name="sending_amount"
                                               id="sending_amount" value="{{ old('sending_amount') }}"
                                               placeholder="0.00">
                                        <span class="input-group-text fw-bold border-0 send_currency_name"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-0 form-design">
                                    <label for="" class="form-label fw-bold mb-0">@lang('You will get')</label>
                                    <div class="input-group align-items-center">
                                        <input type="number" step="any"
                                               class="form-control form--control rounded" id="receiving_amount"
                                               name="receiving_amount" value="{{ old('receiving_amount') }}"
                                               placeholder="0.00">
                                        <span class="input-group-text fw-bold border-0 buy_currency_name"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="rate--txt">
                                    <ul class="transaction-history-content">
                                        <li class="item">
                                            <span>@lang('Send Currency Limit:')</span>

                                            <span class="limit-exchange exchange-limit-text">
                                                <span class="send_currency_limit"></span>
                                                <span class="currency_name send_currency_name"></span>
                                            </span>
                                        </li>

                                        <li class="item">
                                            <span>@lang('Current Rate:')</span>
                                            <p id="result-text" class="rounded exchange-limit-text mb-0"></p>
                                        </li>
                                    </ul>
                                </div>

                                <div class="rate--txt-received">
                                    <ul class="transaction-history-content">
                                        <li class="item">
                                            <span>@lang('Receive Currency Limit:')</span>
                                            <span class="limit-received-exchange exchange-limit-text">+
                                                <span class="buy_currency_limit"></span>
                                                <span class="currency_name buy_currency_name"></span>
                                            </span>
                                        </li>
                                        <li class="item">
                                            <span>@lang('Received Currency Reserve :')</span>
                                            <span class="reserve-amount exchange-limit-text">
                                                <span class="reserve_amount"></span>
                                                <span class="currency_name buy_currency_name"></span>
                                            </span>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn--base w-100" type="submit">
                                    <span class="me-2"> <i class="las la-exchange-alt"></i></span>@lang('Exchange Now')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        "use strict";
        (function($) {
            let sendId, sendMinAmount, sendMaxAmount, sendAmount, sendCurrency, sendCurrencyBuyRate;
            let receivedId, receivedAmount, receivedCurrency, receiveCurrencySellRate;

            $(document).on('click', '.repeat-btn', function() {
                var sendCurrency = $(this).data('send-currency');
                var receiveCurrency = $(this).data('received-currency');

                if (sendCurrency) {
                    $('[name=sending_currency]').val(sendCurrency.id);
                }
                if (receiveCurrency) {
                    $('[name=receiving_currency]').val(receiveCurrency.id);
                }

                sendCurrencyBuyRate = parseFloat(sendCurrency.buy_at);
                receiveCurrencySellRate = parseFloat(receiveCurrency.sell_at);

                let sendCurrencySymbol = sendCurrency.cur_sym;
                let receiveCurrencySymbol = receiveCurrency.cur_sym;

                const sendAmountNumber = 1;
                const calculateExchangeRate = () => {
                    if (!sendCurrencyBuyRate || !receiveCurrencySellRate) return;
                    let amountReceived = (sendCurrencyBuyRate / receiveCurrencySellRate) * sendAmountNumber;
                    let resultText =
                        `1 ${sendCurrencySymbol} = ${amountReceived.toFixed(2)} ${receiveCurrencySymbol}`;
                    $("#result-text").text(resultText);
                };
                calculateExchangeRate();

                $('.send_currency_limit').text(
                    `${parseFloat(sendCurrency.minimum_limit_for_sell).toFixed(2)} - ${parseFloat(sendCurrency.maximum_limit_for_sell).toFixed(2)}`
                );
                $('.send_currency_name').text(sendCurrency.cur_sym);
                $('.send_currency_name_text').text(sendCurrency.name);

                $('.buy_currency_limit').text(
                    `${parseFloat(receiveCurrency.minimum_limit_for_sell).toFixed(2)} - ${parseFloat(receiveCurrency.maximum_limit_for_sell).toFixed(2)}`
                );
                $('.reserve_amount').text(parseFloat(receiveCurrency.reserve).toFixed(2));
                $('.buy_currency_name').text(receiveCurrency.cur_sym);
                $('.buy_currency_name_text').text(receiveCurrency.name);

                var sendAmount = $(this).data('sending-amount');
                var receiveAmount = $(this).data('receiving-amount');
                var sendImage = $(this).data('send-image');
                var receivedImage = $(this).data('received-image');

                $('.received-image').attr('src', receivedImage);
                $('.send-image').attr('src', sendImage);

                $('#sending_amount').val(parseFloat(sendAmount).toFixed(2)).change();
                $('#receiving_amount').val(parseFloat(receiveAmount).toFixed(2)).change();

                $('#transactionModal').modal('show');
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
                let amountReceived = (sendCurrencyBuyRate / receiveCurrencySellRate) * sendAmount;
                $("#receiving_amount").val(amountReceived.toFixed({{ gs('show_number_after_decimal') }}));
            }

            const calculationSendAmount = () => {
                if (!sendId && !receivedId && !sendCurrencyBuyRate && !receiveCurrencySellRate) {
                    return false;
                }
                let amountReceived = (receiveCurrencySellRate / sendCurrencyBuyRate) * receivedAmount;
                $("#sending_amount").val(amountReceived.toFixed({{ gs('show_number_after_decimal') }}));
            }
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .form-design {
            background: #f2f2f2 !important;
            padding: 12px;
            border-radius: 10px;
        }

        .form-design .input-group>.form-control,
        .form-design .input-group>.form-floating,
        .input-group>.form-select {
            background: transparent !important;
            border: transparent !important;
            font-weight: 700;
            font-size: 20px !important;
            padding: 0px;
            height: 20px;
        }

        .form-design .input-group-text {
            background: transparent !important;
        }

        .form-design input::-webkit-outer-spin-button,
        .form-design input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .form-design input[type=number] {
            -moz-appearance: textfield;
        }

        /* exchange design  */
        .exchange-info__thumb img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 1px solid #f2f2f2;
        }

        .exchange-info__icon {
            font-size: 30px;
        }

        .exchange-info__title span {
            font-weight: 600;
            color: #000;
            margin-top: 5px;
        }

        .transaction-history-content .item {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;

        }

        .transaction-history-content .item {
            margin-bottom: 5px;
            padding: 0px
        }

        .transaction-history-content .exchange-limit-text {
            font-weight: 700;
        }

        @media screen and (max-width: 424px) {
            .modal-body {
                padding: 15px !important;
            }

        }
    </style>
@endpush
