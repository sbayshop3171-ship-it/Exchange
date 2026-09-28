@foreach ($currencies as $currency)
    <div class="col-sm-6 col-lg-4">
        <div class="reserve_item">
            <div class="reserve_header">
                <div class="reserve_item__thumb">
                    <img class="currency-img" src="{{ getImage(getFilePath('currency') . '/' . $currency->image, getFileSize('currency')) }}" alt="currency image">
                </div>
                <div class="reserve_item__info">
                    <h6 class="title">{{ __($currency->name) }}</h6>
                    <div class="d-flex flex-wrap align-items-center">
                        <span class="name">@lang('Reserve') : </span>
                        <span class="rate">
                            {{ showAmount($currency->reserve, currencyFormat: false) }}
                            {{ __($currency->cur_sym) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="reserve_body">
                <ul class="reserve_amounts">
                    <li>
                        <span class="name">@lang('Buy Rate') :</span>
                        <span class="rate">{{ showAmount($currency->sell_at) }}</span>
                    </li>
                    <li>
                        <span class="name">@lang('Sell Rate') :</span>
                        <span class="rate">{{ showAmount($currency->buy_at) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endforeach
