@php
    $latestExchange = App\Models\Exchange::desc()->with('sendCurrency', 'receivedCurrency', 'user')->approved()->limit(12)->get();
@endphp

<div class="custom-widget">
    <h5 class="title mb-3">@lang('Latest Exchanges')</h5>
    @if (!$latestExchange->isEmpty())
        <table class="table table--responsive--lg mb-0 table--md fs--13px">
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
                @foreach ($latestExchange as $exchange)
                    <tr>
                        <td> {{ @$exchange->user->fullname }}</td>
                        <td>
                            <div class="table-content">
                                <div class="thumb">
                                    <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}"
                                         class="thumb">
                                </div>
                                <span class="mt-2">{{ __(@$exchange->sendCurrency->name) }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="table-content text-center">
                                <div class="thumb">
                                    <img
                                         src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}">
                                </div>
                                <span class="mt-2">{{ __(@$exchange->receivedCurrency->name) }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="amount">
                                {{ showAmount($exchange->sending_amount, currencyFormat: false) }}
                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                                <i class="las la-arrow-right text--base"></i>
                                {{ showAmount($exchange->receiving_amount, currencyFormat: false) }}
                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="d-block">{{ showDateTime(@$exchange->created_at) }}</span>
                                <span class="text--base">{{ diffForHumans(@$exchange->created_at) }}</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        @include($activeTemplate . 'partials.empty', ['message' => 'No latest exchange found'])
    @endif
</div>
