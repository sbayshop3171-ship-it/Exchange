@php
    $latestExchangeContent = getContent('latest_exchange.content', true);
    $acceptedExchange = App\Models\Exchange::desc()->with('sendCurrency', 'receivedCurrency', 'user')->approved()->take(20)->get();
@endphp
<section class="section-bg padding-top padding-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-header">
                    <h2 class="title">{{ __(@$latestExchangeContent->data_values->heading) }}</h2>
                    <p>{{ __(@$latestExchangeContent->data_values->subheading) }} </p>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card custom--card">
                    @if (!$acceptedExchange->isEmpty())
                        <div class="card-body p-0">
                            <table class="table custom--table table-responsive--md exchange-table">
                                <thead>
                                    <tr>
                                        <th>@lang('User')</th>
                                        <th>@lang('Sent')</th>
                                        <th>@lang('Received')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($acceptedExchange as $exchange)
                                        <tr>
                                            <td>{{ @$exchange->user->fullname }}</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="table-currency-img"
                                                        src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}"
                                                        alt="currency image">
                                                </span>
                                                {{ $exchange->sendCurrency->name }}
                                            </td>
                                            <td>
                                                <span class="thumb">
                                                    <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}"
                                                        class="table-currency-img" alt="currency image">
                                                </span>
                                                <span>
                                                    {{ __($exchange->receivedCurrency->name) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ showAmount($exchange->sending_amount, currencyFormat: false) }}
                                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                                                <i class="la la-arrow-right" aria-hidden="true"></i>
                                                {{ showAmount($exchange->receiving_amount, currencyFormat: false) }}
                                                {{ __($exchange->receivedCurrency->cur_sym) }}
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="d-block">{{ showDateTime($exchange->created_at) }}</span>
                                                    <span>{{ diffForHumans($exchange->created_at) }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @include($activeTemplate . 'partials.empty', [
                            'message' => 'No latest exchange found',
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
