@extends('admin.layouts.app')
@section('panel')
    <form action="{{ route('admin.currency.save', @$currency->id) }}" method="POST" class="disableSubmission" enctype="multipart/form-data">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-3">
                                <label> @lang('Image')</label>
                                <x-image-uploader :imagePath="getImage(getFilePath('currency') . '/' . @$currency->image, getFileSize('currency'))" :size="getFileSize('currency')" class="w-100" id="imageCreate" :required="false" />
                            </div>
                            <div class="col-sm-12 col-lg-8 col-xxl-9">
                                <div class="row">
                                    <div class="col-xxl-4 col-sm-12">
                                        <div class="form-group">
                                            <label>@lang('Currency Name')</label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', @$currency->name) }}" required autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Currency')</label>
                                            <input type="text" name="currency" class="form-control currency" required value="{{ old('currency', @$currency->cur_sym) }}" />
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Payment Gateway')</label> <i class="la la-info-circle" title="@lang('User will send the money by this payment gateway.')"></i>
                                            <select name="payment_gateway" class="form-control select2" data-minimum-results-for-search="-1" required>
                                                <option value="0">@lang('Manual')</option>
                                                @foreach ($gateways as $gateway)
                                                    <option value="{{ $gateway->id }}" @selected(@$currency && $currency->gateway_id == $gateway->id)>
                                                        {{ $gateway->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <label> @lang('Conversion Rate')</label>
                                                @if (gs('currency_api_key'))
                                                    <a href="javascript:void(0)" class="conversionRate mb-1">
                                                        <span class="currency-symbol">{{ $currency->cur_sym ?? '' }}</span>
                                                        @lang('to') {{ __(gs('cur_text')) }}
                                                    </a>
                                                @else
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top" href="https://www.exchangerate-api.com/" target="_blank" title="@lang('Enable the API key to get the automatic conversion rate via exchange rate-API.')">
                                                        @lang('exchangerate-api')
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group">
                                                    <span class=" input-group-text">
                                                        1 <span class="currency-symbol ms-1"></span>
                                                    </span>
                                                    <input type="number" step="any" class="form-control" name="conversion_rate" value="{{ old('conversion_rate', @$currency ? @$currency->conversion_rate : '') }}" required />
                                                    <span class=" input-group-text">{{ gs('cur_text') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Reserve')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" name="reserve" value="{{ old('reserve', @$currency ? @$currency->reserve : '') }}" required />
                                                <span class="currency-symbol input-group-text"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Percent Decrease For Buy')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" name="percent_decrease" value="{{ old('percent_decrease', @$currency ? @$currency->percent_decrease : '') }}" />
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Buy At')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" name="buy_at" value="{{ old('buy_at', @$currency ? @$currency->buy_at : '') }}" />
                                                <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Percent Increase For Sell')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" name="percent_increase" value="{{ old('percent_increase', @$currency ? @$currency->percent_increase : '') }}" required />
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Sell At')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" name="sell_at" value="{{ old('sell_at', @$currency ? @$currency->sell_at : '') }}" required />
                                                <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Add with Automatic Rate')</label>
                                            <i class="la la-info-circle" title="@lang('When the automatic currency exchange rate is updated, this rate will be added to the currency rate')"></i>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control" name="add_automatic_rate" value="{{ old('add_automatic_rate', @$currency ? @$currency->add_automatic_rate : '') }}" required />
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Number of Digits After Decimal Point')</label>
                                            <input type="number" class="form-control" name="show_number_after_decimal" value="{{ old('show_number_after_decimal', @$currency ? @$currency->show_number_after_decimal : '') }}" required />
                                        </div>
                                    </div>

                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Available For sell')</label>
                                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="available_for_sell" {{ @$currency ? ($currency->available_for_sell ? 'checked' : '') : 'checked' }}>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Available For buy')</label>
                                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="available_for_buy" {{ @$currency ? ($currency->available_for_buy ? 'checked' : '') : 'checked' }}>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="form-group">
                                            <label> @lang('Rate Show')</label>
                                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="rate_show" {{ @$currency ? ($currency->show_rate ? 'checked' : '') : 'checked' }}>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Automatic Rate Update')</label>
                                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="automatic_rate_update" {{ @$currency ? ($currency->automatic_rate_update ? 'checked' : '') : 'checked' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 col-md-6">
                <div class="card">
                    <h5 class="card-header bg--info">@lang('Limit & Charge for Sale')</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang('Minimum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="minimum_limit_for_sell" required value="{{ old('minimum_limit_for_sell', @$currency ? @$currency->minimum_limit_for_sell : '') }}" />
                                    <span class="input-group-text currency-symbol d-none"></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Maximum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="maximum_limit_for_sell" required value="{{ old('maximum_limit_for_sell', @$currency ? @$currency->maximum_limit_for_sell : '') }}" />
                                    <span class="input-group-text currency-symbol d-none"></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Fixed Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="fixed_charge_for_sell" required value="{{ old('fixed_charge_for_sell', @$currency ? @$currency->fixed_charge_for_sell : '') }}" />
                                    <div class="input-group-text currency-symbol d-none"></div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Percent Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="percent_charge_for_sell" required value="{{ old('percent_charge_for_sell', @$currency ? @$currency->percent_charge_for_sell : '') }}" />
                                    <div class="input-group-text">@lang('%')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 col-md-6">
                <div class="card">
                    <h5 class="card-header bg--warning">@lang('Limit & Charge to Buy')</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang('Minimum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="minimum_limit_for_buy" required value="{{ old('minimum_limit_for_buy', @$currency ? @$currency->minimum_limit_for_buy : '') }}" />
                                    <span class="input-group-text currency-symbol d-none "></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Maximum Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="maximum_limit_for_buy" required value="{{ old('maximum_limit_for_buy', @$currency ? @$currency->maximum_limit_for_buy : '') }}" />
                                    <span class="input-group-text currency-symbol d-none"></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Fixed Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control rounded" name="fixed_charge_for_buy" required value="{{ old('fixed_charge_for_buy', @$currency ? @$currency->fixed_charge_for_buy : '') }}" />
                                    <div class="input-group-text currency-symbol d-none"></div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Percent Charge')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control" name="percent_charge_for_buy" required value="{{ old('percent_charge_for_buy', @$currency ? @$currency->percent_charge_for_buy : '') }}" />
                                    <div class="input-group-text">@lang('%')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" my-4 forManualGateway">
            <div class="card">
                <h5 class="card-header">
                    @lang('Instruction')
                    <i class="fa fa-info-circle text--primary" title="@lang('Write the payment instruction here. Users will see the instruction while exchanging money.')"></i>
                </h5>
                <div class="card-body">
                    <div class="form-group editor-wrapper">
                        <textarea rows="8" class="form-control nicEdit" name="instruction">{{ old('instruction', @$currency->instruction) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 my-4">
            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
        </div>
    </form>
@endsection

@push('breadcrumb-plugins')
    <x-back :route="route('admin.currency.index')" />
@endpush


@push('script')
    <script>
        "use strict";
        (function($) {

            let $butAt = $(`input[name=buy_at]`);
            let $sellAt = $(`input[name=sell_at]`);
            let $pDecrease = $(`input[name=percent_decrease]`);
            let $pIncrease = $(`input[name=percent_increase]`);

            let currency = $('.currency').val();

            let conversionRate = Number({{ @$currency->conversion_rate ?? 0 }});
            let percentDecrease = Number($pDecrease.val());
            let percentIncrease = Number($pIncrease.val());
            let sellAt = Number($sellAt.val());
            let buyAt = Number($butAt.val());

            const roundNumber = (number, round = 8) => {
                return parseFloat(parseFloat(number).toFixed(round).toString());
            }

            $('[name=percent_decrease]').on('input', function(e) {
                const value = Number($(this).val());
                if (value >= 100 || value < 0) return;
                percentDecrease = value;
                calculateBuyAt();
            });

            $('[name=buy_at]').on('input', function(e) {
                const value = Number($(this).val());
                buyAt = value;
                calculatePercentDecrease();
            });

            const calculateBuyAt = () => {
                const percentDecreaseValue = conversionRate / 100 * percentDecrease;
                buyAt = roundNumber(conversionRate - percentDecreaseValue);
                $('input[name=buy_at]').val(roundNumber(buyAt));
            }

            const calculatePercentDecrease = () => {
                if (buyAt) {
                    const diff = conversionRate - buyAt;
                    const percentDecreaseValue = diff / conversionRate * 100;
                    $('input[name=percent_decrease]').val(roundNumber(percentDecreaseValue));
                } else {
                    $('input[name=percent_decrease]').val("");
                }
            }

            $('[name=percent_increase]').on('input', function(e) {
                const value = Number($(this).val());
                if (value < 0) return;
                percentIncrease = value;
                calculateSellAt();
            });

            const calculateSellAt = () => {
                const percentIncreaseValue = conversionRate / 100 * percentIncrease;
                sellAt = roundNumber(conversionRate + percentIncreaseValue);
                $('input[name=sell_at]').val(roundNumber(sellAt));
            }

            $('[name=sell_at]').on('input', function(e) {
                const value = Number($(this).val());
                sellAt = value;
                calculatePercentIncrease();
            });

            const calculatePercentIncrease = () => {
                if (sellAt) {
                    const diff = sellAt - conversionRate;
                    const percentIncreaseValue = diff / conversionRate * 100;
                    $('input[name=percent_increase]').val(roundNumber(percentIncreaseValue));
                } else {
                    $('input[name=percent_increase]').val("");
                }
            }

            $('input[name=conversion_rate]').on('input', function() {
                conversionRate = Number($(this).val());
                @if (!@$currency)
                    calculateSellAt();
                    calculateBuyAt();
                @endif

                calculatePercentDecrease();
                calculatePercentIncrease();
            });

            const currencySymbol = () => {
                currency = $('.currency').val();
                if (currency && currency.length > 0) {
                    currency = currency.toUpperCase();
                    $('.currency').val(currency);
                    $('.currency-symbol').removeClass('d-none');
                    $('.currency-symbol').parent().find('input').removeClass('rounded');
                    $('.currency-symbol').text(currency);
                } else {
                    $('.currency-symbol').addClass('d-none');
                    $('.currency-symbol').parent().find('input').addClass('rounded');
                }
            }
            @if (@$currency)
                currencySymbol();
            @endif

            $('.currency').on('input', currencySymbol);

            $('[name=payment_gateway]').on('change', function() {
                if (this.value != 0) {
                    $('.forManualGateway').addClass('d-none');
                } else {
                    $('body .editor-wrapper').find('div').first().css({
                        width: "100%"
                    });
                    $('body .editor-wrapper').children('div').last().css({
                        width: "100%"
                    });
                    $('body .editor-wrapper').find('.nicEdit-main').css({
                        width: "100%",
                        "min-height": "180px"
                    });
                    $('.forManualGateway').removeClass('d-none');
                }
            }).change();

            $('.conversionRate').on('click', function() {
                currency = $('.currency').val();
                let url = "{{ route('admin.currency.import.conversion') }}";
                if (currency) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            currency: currency,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            $('[name=conversion_rate]').val(data[0].conversion_rate);
                            conversionRate = data[0].conversion_rate;
                            buyAt = conversionRate;
                            sellAt = conversionRate;
                            percentIncrease = 0;
                            percentDecrease = 0;
                            calculateSellAt();
                            calculateBuyAt();
                            calculatePercentDecrease();
                            calculatePercentIncrease();
                        }
                    });
                }
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .conversionRate:hover .currency-symbol {
            color: #4634ff;
        }
    </style>
@endpush
