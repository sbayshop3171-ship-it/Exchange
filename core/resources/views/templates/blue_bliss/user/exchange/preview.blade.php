@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row gy-4">
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <h6 class="text-center"> @lang('Exchange ID: ') <span class="text-muted">#{{ $exchange->exchange_id }}</span></h6>
                        <p class="mt-1 fw-bold text-center text--warning">
                            @lang('Send')
                            {{ number_format($exchange->sending_amount + $exchange->sending_charge, $exchange->sendCurrency->show_number_after_decimal) }}
                            {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }} @lang('via')
                            {{ __(@$exchange->sendCurrency->name) }} @lang('to get')
                            {{ number_format($exchange->receiving_amount - $exchange->receiving_charge, $exchange->receivedCurrency->show_number_after_decimal) }}
                            {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }} @lang('via')
                            {{ __(@$exchange->receivedCurrency->name) }}
                        </p>

                        @if ($exchange->expired_at)
                            <div class="expire-time text-center">
                                @if ($expired)
                                    <span class="text-danger">
                                        <i class="las la-exclamation-circle"></i>
                                        {{ __(@$expireMessage) }}
                                    </span>
                                @else
                                    <span>
                                        <i class="las la-exclamation-circle"></i>
                                        {{ __(@$expireMessage) }}
                                    </span>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
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
                                        <img class="currency__image" src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}" alt="currency image">
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
                                    {{ number_format(@$exchange->sending_amount, @$exchange->sendCurrency->show_number_after_decimal) }}
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
                                    {{ number_format(@$exchange->sending_charge, @$exchange->sendCurrency->show_number_after_decimal) }}
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
                                    {{ number_format($exchange->sending_amount + $exchange->sending_charge, @$exchange->sendCurrency->show_number_after_decimal) }}
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
                                        <img class="currency__image" src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}" alt="currency image">
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
                                    <small class="fw-bold">@lang('Receivable Amount After Charge')</small>
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
            <div class="col-md-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <form method="post" action="{{ route('user.exchange.confirm') }}" enctype="multipart/form-data" class="disableSubmission">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">
                                    @lang('Your')
                                    {{ __(@$exchange->receivedCurrency->name) }}
                                    @lang('Wallet Number/ID')
                                </label>
                                <input type="text" class="form-control form--control" name="wallet_id" required>
                            </div>
                            <x-viser-form identifier="id" identifierValue="{{ @$exchange->receivedCurrency->userDetailsData->id }}" />
                            <button class="btn btn--base w-100 confirmationBtn" type="submit" @disabled($expired)>
                                @lang('Confirm Exchange')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .expire-time span {
            font-weight: 700;
        }
    </style>
@endpush
