@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card custom--card">
                    @if (!$exchanges->isEmpty())
                        <div class="card-body p-0">
                            <table class="table custom--table table-responsive--md">
                                <thead>
                                    <tr>
                                        <th>@lang('Exchange ID')</th>
                                        <th>@lang('Send')</th>
                                        <th>@lang('Received')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exchanges as $exchange)
                                        <tr>
                                            <td>{{ $exchange->exchange_id }}</td>
                                            <td>
                                                <div class="thumb">
                                                    <img class="table-currency-img" src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}">
                                                </div>
                                                {{ __(@$exchange->sendCurrency->name) }}
                                            </td>
                                            <td>
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}" class="table-currency-img">
                                                </div>
                                                {{ __(@$exchange->receivedCurrency->name) }}
                                            </td>
                                            <td>
                                                {{ number_format($exchange->sending_amount, $exchange->sendCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                                                <i class="las la-arrow-right text--base"></i>
                                                {{ number_format($exchange->receiving_amount, $exchange->receivedCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-end">
                                                    <span>{{ showDateTime(@$exchange->created_at) }}</span>
                                                    <span class="text--base">{{ diffForHumans(@$exchange->created_at) }}</span>
                                                </div>
                                            </td>
                                            <td>@php echo $exchange->badgeData(); @endphp</td>
                                            <td>
                                                <a href="{{ route('user.exchange.details', $exchange->exchange_id) }}" class="btn btn--base-outline btn-sm" data-reason="{{ $exchange->cancle_reason }}">
                                                    <i class="fa fa-desktop"></i> @lang('Details')
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @include($activeTemplate . 'partials.empty', [
                            'message' => 'No exchange found',
                        ])
                    @endif
                </div>
                @if ($exchanges->hasPages())
                    <div class="py-3 custom__paginate">
                        {{ paginateLinks($exchanges) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
