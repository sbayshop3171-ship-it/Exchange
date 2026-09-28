@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form disableSubmission">
                    @csrf
                    <div class="gateway-card">
                        <div class="text-center card-header flex-column py-3">
                            <h5 class="d-block">@lang('Withdraw Balance')</h5>
                            <span>@lang('Your Current Balance Is:') {{ showAmount($user->balance) }}</span>
                        </div>
                        <div class="row justify-content-center gy-sm-4 gy-3">
                            <div class="col-lg-6">
                                <div class="payment-system-list is-scrollable gateway-option-list">
                                    @foreach ($currencies as $data)
                                        <label for="{{ titleToKey($data->name) }}_{{ $data->cur_sym }}"
                                            class="payment-item @if ($loop->index > 4) d-none @endif gateway-option">
                                            <div class="payment-item__info">
                                                <span class="payment-item__check"></span>
                                                <span class="payment-item__name">
                                                    {{ __($data->name) }} -
                                                    {{ $data->cur_sym }}
                                                </span>
                                            </div>
                                            <div class="payment-item__thumb">
                                                <img class="payment-item__thumb-img"
                                                    src="{{ getImage(getFilePath('currency') . '/' . $data->image) }}"
                                                    alt="@lang('payment-thumb')">
                                            </div>
                                            <input class="payment-item__radio gateway-input"
                                                id="{{ titleToKey($data->name) }}_{{ $data->cur_sym }}" hidden
                                                data-gateway='@json($data)' type="radio"
                                                name="currency_id" value="{{ $data->id }}"
                                                @if (old('currency_id')) @checked(old('currency_id') == $data->id) @else @checked($loop->first) @endif
                                                data-min-amount="{{ gs('cur_sym') }}{{ showAmount($data->minimum_limit_for_sell * $data->sell_at, currencyFormat: false) }}"
                                                data-max-amount="{{ gs('cur_sym') }}{{ showAmount($data->maximum_limit_for_sell * $data->sell_at, currencyFormat: false) }}">
                                        </label>
                                    @endforeach
                                    @if ($currencies->count() > 4)
                                        <button type="button" class="payment-item__btn more-gateway-option">
                                            <p class="payment-item__btn-text">@lang('Show All Payment Options')</p>
                                            <span class="payment-item__btn__icon"><i
                                                    class="fas fa-chevron-down"></i></i></span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="payment-system-list">
                                    <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text mb-0">@lang('Send Amount')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <div class="deposit-info__input-group input-group">
                                                <span class="deposit-info__input-group-text">{{ gs('cur_sym') }}</span>
                                                <input type="text" class="form-control form--control amount"
                                                    name="amount" placeholder="@lang('00.00')"
                                                    value="{{ old('amount') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="deposit-info mt-3">
                                        <div class="deposit-info__title">
                                            <p class="text mb-0">@lang('Get Amount')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <div class="deposit-info__input-group input-group">
                                                <span class="deposit-info__input-group-text getAmountCurrency"></span>
                                                <input type="text" class="form-control form--control getAmount"
                                                    name="get_amount" value="{{ old('get_amount') }}"
                                                    placeholder="@lang('00.00')" autocomplete="off" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text has-icon"> @lang('Limit')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text"><span class="gateway-limit">@lang('0.00')</span> </p>
                                        </div>
                                    </div>
                                    <div class="deposit-info">
                                        <div class="deposit-info__title">
                                            <p class="text has-icon">@lang('Processing Charge')
                                                <span data-bs-toggle="tooltip" title="@lang('Processing charge for withdraw method')"
                                                    class="processing-fee-info">
                                                    <i class="las la-info-circle"></i>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text">
                                                <span class="processing-fee">@lang('0.00')</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="deposit-info total-amount pt-3">
                                        <div class="deposit-info__title">
                                            <p class="text">@lang('Receivable')</p>
                                        </div>
                                        <div class="deposit-info__input">
                                            <p class="text">
                                                <span class="final-amount">@lang('0.00')</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="sending_info d-none">
                                        <div class="deposit-info total-amount pt-3 mb-3">
                                            <div class="deposit-info__title">
                                                <h6 class="text">@lang('Sending Information')</h6>
                                            </div>
                                        </div>
                                        <div class="user_input form-group"> </div>
                                    </div>
                                    <button type="submit" class="btn btn--base w-100" disabled>
                                        @lang('Confirm Withdraw')
                                    </button>
                                    <div class="info-text pt-3">
                                        <p class="text m-0">
                                            @lang('Safely withdraw your funds using our highly secure process and various withdrawal method')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            var sendAmount = $('.amount').val() || 0;
            var gateway, minAmount, maxAmount, amount;

            $('.amount').on('input', function(e) {
                sendAmount = sendAmount = isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
                amount = sendAmount == 0 ? 0 : sendAmount / gateway.sell_at;
                if (!amount) {
                    amount = 0;
                }
                calculation();
            });

            $('.gateway-input').on('change', function(e) {
                gatewayChange();
                loadGateway();
            });

            function gatewayChange() {
                let gatewayElement = $('.gateway-input:checked');
                let methodCode = gatewayElement.val();

                gateway = gatewayElement.data('gateway');
                minAmount = gatewayElement.data('min-amount');
                maxAmount = gatewayElement.data('max-amount');
                $('.getAmountCurrency').text(gateway.cur_sym);
                amount = sendAmount == 0 ? 0 : sendAmount / gateway.sell_at;

                let processingFeeInfo =
                    `${parseFloat(gateway.percent_charge_for_sell).toFixed(2)}% with ${parseFloat(gateway.fixed_charge_for_sell).toFixed(2)} ${gateway.cur_sym} charge for processing fees`
                $(".processing-fee-info").attr("data-bs-original-title", processingFeeInfo);
                calculation();
            }

            gatewayChange();
            loadGateway();

            $(".more-gateway-option").on("click", function(e) {
                let paymentList = $(".gateway-option-list");
                paymentList.find(".gateway-option").removeClass("d-none");
                $(this).addClass('d-none');
                paymentList.animate({
                    scrollTop: (paymentList.height() - 60)
                }, 'slow');
            });

            function calculation() {
                if (!gateway) return;
                $(".gateway-limit").text(minAmount + " - " + maxAmount);
                let percentCharge = 0;
                let fixedCharge = 0;
                let totalPercentCharge = 0;

                if (amount) {
                    percentCharge = parseFloat(gateway.percent_charge_for_sell);
                    fixedCharge = parseFloat(gateway.fixed_charge_for_sell);
                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);
                }

                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
                let totalAmount = parseFloat((amount || 0) - totalPercentCharge - fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(gateway.show_number_after_decimal) + ' ' + gateway.cur_sym);
                $(".processing-fee").text(totalCharge.toFixed(gateway.show_number_after_decimal) + ' ' + gateway.cur_sym);
                $("input[name=currency]").val(gateway.currency);
                $('.getAmount').val(amount.toFixed(gateway.show_number_after_decimal));

                if (amount < Number(gateway.min_limit) || amount > Number(gateway.max_limit)) {
                    $(".withdraw-form button[type=submit]").attr('disabled', true);
                } else {
                    $(".withdraw-form button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}") {
                    $('.withdraw-form').addClass('adjust-height')
                    $(".gateway-conversion").find('.deposit-info__input .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                } else {
                    $('.withdraw-form').removeClass('adjust-height')
                }
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            $('.gateway-input').change();

            function loadGateway() {
                let url = `{{ route('user.withdraw.currency.user.data', ':id') }}`;
                url = url.replace(':id', gateway.id);
                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {
                        if (response.success) {
                            if (response.html) {
                                $('.user_input').html(response.html);
                                $('.sending_info').removeClass('d-none')
                            } else {
                                $('.user_input').html('');
                                $('.sending_info').addClass('d-none')
                            }
                        } else {
                            notify('error', response.message || "@lang('Something went the wrong')")
                        }
                    }
                })
            }
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .form--control {
            height: 44px;
        }
    </style>
@endpush
