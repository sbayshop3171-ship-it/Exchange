@php
    $reserveCurrencies = App\Models\Currency::enabled()
        ->availableForSell()
        ->availableForBuy()
        ->where('show_rate', Status::YES)
        ->where('reserve', '>', 0)
        ->asc('name')
        ->get();
@endphp

<div class="custom-widget">
    <h6 class="custom-widget-title mb-3">@lang('Our Reserves')</h6>
    @if (!$reserveCurrencies->isEmpty())
        <div class="currency-wrapper">
            <div class="currency-wrapper__header">
                <p class="currency-wrapper__name">@lang('Currency')</p>
                <div class="currency-wrapper__content">
                    <span class="buy-sell">@lang('Reserved')</span>
                </div>
            </div>
            <ul class="currency-list">
                @foreach ($reserveCurrencies as $currency)
                    <li class="currency-list__item">
                        <div class="currency-list__wrapper">
                            <div class="currency-list__left">
                                <div class="currency-list__thumb">
                                    <img src="{{ getImage(getFilePath('currency') . '/' . @$currency->image, getFileSize('currency')) }}"
                                        alt="currency-image" class="thumb">
                                </div>
                                <span class="currency-list__text">
                                    {{ __($currency->name) }} - {{ __($currency->cur_sym) }}
                                </span>
                            </div>
                            <div class="currency-list__content">
                                <span class="buy-sell two">
                                    {{ number_format($currency->reserve, $currency->show_number_after_decimal) }} {{  __($currency->cur_sym) }}
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        @include($activeTemplate . 'partials.empty', ['message' => 'No reserves found'])
    @endif
</div>
