@php
    $currencies = App\Models\Currency::enabled()->availableForSell()->availableForBuy()->desc()->get();
    $sellAlertCurrencies = App\Models\Currency::enabled()->availableForSell()->orderBy('name')->get();
    $buyAlertCurrencies = App\Models\Currency::enabled()->availableForBuy()->orderBy('name')->get();
@endphp

<div class="custom-widget mb-4">
    <h6 class="custom-widget-title mb-3 d-flex justify-content-between align-items-center">
        @lang('Exchange Rates Now')
        @if (gs('automatic_currency_rate_update'))
            <a href="#" class="text--base gate-rate-alert text-decoration-underline" data-bs-toggle="modal"
               data-bs-target="#gateAlertModal">
                @lang('Gate Rate Alert')
            </a>
        @endif
    </h6>
    @if (!$currencies->isEmpty())
        <div class="currency-wrapper">
            <div class="currency-wrapper__header">
                <p class="currency-wrapper__name">@lang('Currency')</p>
                <div class="currency-wrapper__content">
                    <span class="buy-sell">@lang('Buy At')</span>
                    <span class="buy-sell">@lang('Sell At')</span>
                </div>
            </div>
            <ul class="currency-list">
                @foreach ($currencies as $currency)
                    <li class="currency-list__item">
                        <div class="currency-list__wrapper">
                            <div class="currency-list__left">
                                <div class="currency-list__thumb">
                                    <img src="{{ getImage(getFilePath('currency') . '/' . $currency->image, getFileSize('currency')) }}"
                                         class="thumb">
                                </div>
                                <span class="currency-list__text">
                                    {{ __($currency->name) }} - {{ __($currency->cur_sym) }}
                                </span>
                            </div>
                            <div class="currency-list__content">
                                <span
                                      class="buy-sell">{{ gs('cur_sym') }}{{ number_format($currency->sell_at, $currency->show_number_after_decimal) }}</span>
                                <span
                                      class="buy-sell">{{ gs('cur_sym') }}{{ number_format($currency->buy_at, $currency->show_number_after_decimal) }}</span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        @include($activeTemplate . 'partials.empty', ['message' => 'No exchange rate found'])
    @endif
</div>

<div class="modal fade" id="gateAlertModal" tabindex="-1" aria-labelledby="gateAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gateAlertModalLabel">@lang('Gate Rate Alert')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('exchange.get.alert') }}" method="POST" id="gate-rate-alert-form"
                      class="disableSubmission">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fromAlert" class="form-label">@lang('From Currency')</label>
                            <select required class="select2-form-modal form-select form--control" name="from_currency"  id="fromAlert">
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($sellAlertCurrencies as $sellAlertCurrency)
                                    <option value="{{ $sellAlertCurrency->id }}"
                                            data-buy="{{ getAmount($sellAlertCurrency->buy_at) }}"
                                            data-currency-sell="{{ @$sellAlertCurrency->cur_sym }}"
                                            data-image="{{ getImage(getFilePath('currency') . '/' . @$sellAlertCurrency->image, getFileSize('currency')) }}">
                                        {{ __($sellAlertCurrency->name) }} - {{ __($sellAlertCurrency->cur_sym) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="toAlert" class="form-label">@lang('To Currency')</label>
                            <select class="select2-form-modal form-select form--control" name="to_currency"
                                    id="toAlert" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                @foreach ($buyAlertCurrencies as $buyAlertCurrency)
                                    <option value="{{ $buyAlertCurrency->id }}"
                                            data-sell="{{ getAmount($buyAlertCurrency->sell_at) }}"
                                            data-currency-buy="{{ @$buyAlertCurrency->cur_sym }}"
                                            data-after-number-decimal="{{ @$buyAlertCurrency->show_number_after_decimal }}"
                                            data-image="{{ getImage(getFilePath('currency') . '/' . @$buyAlertCurrency->image, getFileSize('currency')) }}">
                                        {{ __($buyAlertCurrency->name) }} - {{ __($buyAlertCurrency->cur_sym) }}
                                    </option>
                                @endforeach
                            </select>
                            <code id="result-text" class="text--base rounded mt-3"></code>
                        </div>
                        <div class="col-md-6">
                            <label for="alert_email" class="form-label">@lang('Send notification to')</label>
                            <input type="email" class="form-control form--control rounded" name="alert_email"
                                   id="alert_email" placeholder="@lang('Enter your email')" required>
                        </div>
                        <div class="col-md-6">
                            <label for="target_rate" class="form-label">@lang('Rate of more than')</label>
                            <input type="number" step="any" class="form-control form--control rounded"
                                   name="target_rate" id="target_rate" placeholder="0.00" required>
                        </div>
                        <div class="col-md-12">
                            <label for="expire_time" class="form-label">@lang('Notification Cancellation')</label>
                            <select class="form-select form--control" name="expire_time" id="expire_time" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                <option value="6">@lang('6 hours')</option>
                                <option value="12">@lang('12 hours')</option>
                                <option value="24">@lang('24 hours')</option>
                                <option value="week">@lang('1 week')</option>
                                <option value="month">@lang('1 month')</option>
                                <option value="3-months">@lang('3 months')</option>
                            </select>
                        </div>
                        <div class="col-md-12 text-center">
                            <button class="btn btn--base mt-3 w-100 rounded" type="submit"><i class="las la-bell me-2"></i>@lang('Enable Notification')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        "use strict";
        (function($) {
            document.addEventListener("DOMContentLoaded", function() {
                const currencyLists = document.querySelectorAll(".currency-list");
                
                currencyLists.forEach(singleList => {
                    const items = singleList.querySelectorAll(".currency-list__item");
                    if (items.length > 7) {
                        $(singleList).slick({
                            autoplay: true,
                            dots: false,
                            infinite: true,
                            speed: 3000,
                            slidesToShow: 7,
                            arrows: false,
                            slidesToScroll: 6,
                            cssEase: "linear",
                            vertical: true,
                            autoplaySpeed: 0,
                            verticalSwiping: true,
                            swipeToSlide: true,
                            swipe: true,
                            focusOnHover: true,
                            pauseOnHover: true,
                        });
                    }
                });
            });


            function formatState(state) {
                if (!state.id) return state.text;
                return $('<img class="ms-1"   src="' + $(state.element).data('image') + '"/> <span class="ms-3">' +
                    state.text + '</span>');
            }

            $('.select2-form-modal').select2({
                templateResult: formatState,
                width: "100%",
                dropdownParent: $('#gateAlertModal')
            });

            let sendCurrencyBuyRate, receiveCurrencySellRate;
            let sendCurrencySymbol, receiveCurrencySymbol,numberAfterDecimal;
            const sendAmount = 1;

            const calculateExchangeRate = () => {
                if (!sendCurrencyBuyRate || !receiveCurrencySellRate) return;
                let amountReceived = (sendCurrencyBuyRate / receiveCurrencySellRate) * sendAmount;
                let resultText = `1 ${sendCurrencySymbol} = ${amountReceived.toFixed(numberAfterDecimal)} ${receiveCurrencySymbol}`;
                $("#result-text").text(resultText);
            };

            $('#fromAlert').on('change', function() {
                sendCurrencyBuyRate = parseFloat($(this).find(':selected').data('buy'));
                sendCurrencySymbol = $(this).find(':selected').data('currency-sell');
                calculateExchangeRate();
            });

            $('#toAlert').on('change', function() {
                receiveCurrencySellRate = parseFloat($(this).find(':selected').data('sell'));
                receiveCurrencySymbol = $(this).find(':selected').data('currency-buy');
                numberAfterDecimal = $(this).find(':selected').data('after-number-decimal');
                calculateExchangeRate();
            });


        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .gate-rate-alert {
            font-size: 15px;
        }

        .gate-rate-alert:hover {
            color: hsl(var(--body)) !important;
        }
    </style>
@endpush
