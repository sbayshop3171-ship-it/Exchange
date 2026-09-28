@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="card custom--card">
            <div class="card-body p-0">
                @if (!$exchanges->isEmpty())
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>@lang('Exchange ID')</th>
                                <th>@lang('Send')</th>
                                <th>@lang('Received')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exchanges as $exchange)
                                <tr>
                                    <td>{{ $exchange->exchange_id }}</td>
                                    <td>
                                        <div class="thumb">
                                            <img class="table-currency-img"
                                                 src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}">
                                        </div>
                                        <span>{{ __(@$exchange->sendCurrency->name) }}</span>
                                    </td>
                                    <td>
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}"
                                                 class="table-currency-img">
                                        </div>
                                        <span>{{ __(@$exchange->receivedCurrency->name) }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            {{ number_format($exchange->sending_amount, $exchange->sendCurrency->show_number_after_decimal) }}
                                            {{ __(@$exchange->sendCurrency->cur_sym) }}
                                            <i class="las la-arrow-right text--base"></i>
                                            {{ number_format($exchange->receiving_amount, $exchange->receivedCurrency->show_number_after_decimal) }}
                                            {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                        </div>
                                    </td>
                                    <td> @php echo $exchange->badgeData(); @endphp</td>
                                    <td>
                                        <span class="d-block">{{ showDateTime($exchange->created_at) }}</span>
                                        <span class="text--base">{{ diffForHumans($exchange->created_at) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('user.exchange.details', $exchange->exchange_id) }}"
                                           class="btn btn--outline-base btn-sm"
                                           data-reason="{{ $exchange->cancle_reason }}">
                                            <i class="las la-desktop"></i> @lang('Details')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @include($activeTemplate . 'partials.empty', ['message' => 'No Exchange Found!'])
                @endif
            </div>
        </div>
        @if ($exchanges->hasPages())
            <div class="mt-3">
                {{ paginateLinks($exchanges) }}
            </div>
        @endif
    </div>
@endsection
