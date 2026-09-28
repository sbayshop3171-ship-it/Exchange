<div class="info-box">
    <div class="row gy-4">
        <div class="col-lg-6">
            <h6>@lang('Sending Details')</h6>
            <ul class="list-group custom--list-group list-group-flush">
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-method-icon />
                        </span>
                        <small class="text-muted">@lang('Method')</small>
                    </div>
                    <span class="d-flex align-items-center">
                        <div class="thumb me-2">
                            <img class="table-currency-img"
                                 src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}">
                        </div>
                        <span class="fw-bold">{{ __(@$exchange->sendCurrency->name) }}</span>
                    </span>
                </li>
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-currency-icon />
                        </span>
                        <small class="text-muted">@lang('Currency')</small>
                    </div>
                    <span class="fw-bold">{{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}</span>
                </li>
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-amount-icon />
                        </span>
                        <small class="text-muted">@lang('Sending Amount')</small>
                    </div>
                    <span class="fw-bold">
                        {{ showAmount(@$exchange->sending_amount, currencyFormat: false) }}
                        {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                    </span>
                </li>
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-charge-icon />
                        </span>
                        <small class="text-muted">@lang('Charge')</small>
                    </div>
                    <span class="fw-bold text--danger">
                        {{ showAmount(@$exchange->sending_charge, currencyFormat: false) }}
                        {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                    </span>
                </li>
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-total-icon />
                        </span>
                        <small class="text-muted">@lang('Total Sending With Charge')</small>
                    </div>
                    <span class="fw-bold">
                        {{ showAmount($exchange->sending_amount + @$exchange->sending_charge, currencyFormat: false) }}
                        {{ __(ucfirst(@$exchange->sendCurrency->cur_sym)) }}
                    </span>
                </li>
            </ul>
        </div>
        <div class="col-lg-6">
            <h6>@lang('Receiving Details')</h6>
            <ul class="list-group custom--list-group list-group-flush">
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-method-icon />
                        </span>
                        <small class="text-muted">@lang('Method')</small>
                    </div>
                    <span class="d-flex align-items-center">
                        <div class="thumb me-2">
                            <img class="table-currency-img"
                                 src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}">
                        </div>
                        <span class="fw-bold">{{ __(@$exchange->receivedCurrency->name) }}</span>
                    </span>
                </li>
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-currency-icon />
                        </span>
                        <small class="text-muted">@lang('Currency')</small>
                    </div>
                    <span class="fw-bold">{{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}</span>
                </li>
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-amount-icon />
                        </span>
                        <small class="text-muted">@lang('Receiving Amount')</small>
                    </div>
                    <span class="fw-bold">
                        {{ showAmount(@$exchange->receiving_amount, currencyFormat: false) }}
                        {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                    </span>
                </li>
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-charge-icon />
                        </span>
                        <small class="text-muted">@lang('Charge')</small>
                    </div>
                    <span class="text--danger fw-bold">
                        {{ showAmount(@$exchange->receiving_charge, currencyFormat: false) }}
                        {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                    </span>
                </li>
                <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                    <div class="d-flex align-items-center gap-2">
                        <span class="svg__icon">
                            <x-total-icon />
                        </span>
                        <small class="text-muted">@lang('Total Receiving After Charge')</small>
                    </div>
                    <span class="fw-bold">
                        {{ showAmount(@$exchange->receiving_amount - @$exchange->receiving_charge, currencyFormat: false) }}
                        {{ __(ucfirst(@$exchange->receivedCurrency->cur_sym)) }}
                    </span>
                </li>
            </ul>
        </div>
        <div class="col-lg-12">
            <ul class="list-group list-group-flush">
                <li class="list-group-item ps-0  justify-content-between flex-wrap  d-flex border-dotted">
                    <span>@lang('Exchange ID')</span>
                    <span class="fw-bold">{{ __($exchange->exchange_id) }}</span>
                </li>
                <li class="list-group-item ps-0  justify-content-between flex-wrap  d-flex border-dotted">
                    <span>@lang('Your ') <span
                              class="text--base">{{ __(@$exchange->receivedCurrency->name) }}</span>
                        @lang('Wallet ID/Number')</span>
                    <span class="fw-bold">{{ __($exchange->wallet_id) }}</span>
                </li>
                @if ($exchange->status == Status::EXCHANGE_APPROVED)
                    <li class="list-group-item ps-0  justify-content-between flex-wrap  d-flex border-dotted">
                        <span>@lang('Admin Transaction/Wallet Number')</span>
                        <span class="fw-bold">{{ $exchange->admin_trx_no }}</span>
                    </li>
                @endif
                <li class="list-group-item ps-0  justify-content-between flex-wrap  d-flex border-dotted">
                    <span>@lang('Status')</span>
                    <span class="text-end">@php echo $exchange->badgeData() @endphp</span>
                </li>
                <li class="list-group-item ps-0  justify-content-between flex-wrap  d-flex border-dotted">
                    <span>@lang('Exchange Date')</span>
                    <div class="text-end">
                        <span class="d-block">{{ showDateTime($exchange->created_at) }}</span>
                        <span>{{ diffForHumans($exchange->created_at) }}</span>
                    </div>
                </li>
                @if ($exchange->admin_feedback != null)
                    <li class="list-group-item ps-0  justify-content-between flex-wrap  d-flex border-dotted">
                        @if ($exchange->status == Status::EXCHANGE_CANCEL)
                            <span>@lang('Failed Reason')</span>
                        @else
                            <span>@lang('Admin Feedback')</span>
                        @endif
                        <span class="text-end">{{ __($exchange->admin_feedback) }}</span>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
