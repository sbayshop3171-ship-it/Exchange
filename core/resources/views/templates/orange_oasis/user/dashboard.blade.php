@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kycContent = getContent('kyc_content.content', true);
    @endphp

    <div class="container">
        <div class="notice"></div>
        <div class="row gy-4 mb-4">
            @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
                <div class="col-12">
                    <div class="alert alert--danger" role="alert">
                        <div class="alert__icon"><i class="fas fa-file-signature"></i></div>
                        <p class="alert__message m-0">
                            <span class="fw-bold">@lang('KYC Documents Rejected')</span><br>
                            <small>
                                <i>
                                    {{ __(@$kycContent->data_values->reject) }}
                                    <a class="text--base" data-bs-toggle="modal" data-bs-target="#kycRejectionReason"
                                       href="javascript::void(0)">
                                        @lang('Click here')
                                    </a>
                                    @lang('to show the reason').<br>
                                    <a class="text--base" href="{{ route('user.kyc.form') }}">
                                        @lang('Click Here')
                                    </a>
                                    @lang('to Re-submit Documents').
                                </i>
                            </small>
                        </p>
                    </div>
                </div>
            @elseif($user->kv == Status::KYC_UNVERIFIED)
                <div class="col-12">
                    <div class="alert alert--info" role="alert">
                        <div class="alert__icon"><i class="fas fa-file-signature"></i></div>
                        <p class="alert__message m-0">
                            <span class="fw-bold">@lang('KYC Verification Required')</span><br>
                            <small>
                                <i>
                                    {{ __(@$kycContent->data_values->unverified_content) }}
                                    <a class="text--base" href="{{ route('user.kyc.form') }}">
                                        @lang('Click here')
                                    </a>
                                </i>
                            </small>
                        </p>
                    </div>
                </div>
            @elseif($user->kv == Status::KYC_PENDING)
                <div class="col-12">
                    <div class="alert alert--warning" role="alert">
                        <div class="alert__icon"><i class="fas fa-user-check"></i></div>
                        <p class="alert__message m-0">
                            <span class="fw-bold">@lang('KYC Verification Pending')</span><br>
                            <small>
                                <i>
                                    {{ __(@$kycContent->data_values->pending_content) }}
                                    <a class="text--base" href="{{ route('user.kyc.data') }}">
                                        @lang('Click here')
                                    </a>
                                    @lang('to see your submitted information')
                                </i>
                            </small>
                        </p>
                    </div>
                </div>
            @endif
        </div>
        <div class="d-flex flex-wrap gap-3">
            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-sync"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Approved Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['approved'] }}</h3><small>{{ showAmount($exchange['approved_amount']) }}</small>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-undo-alt"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Pending Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['pending'] }}</h3><small>{{ showAmount($exchange['pending_amount']) }}</small>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-window-close"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Cancel Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['cancel'] }}</h3><small>{{ showAmount($exchange['cancel_amount']) }}</small>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-sync-alt"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Refund Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['refunded'] }}</h3><small>{{ showAmount($exchange['refunded_amount']) }}</small>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-exchange-alt"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Total Exchange')</p>
                    <h3 class="dashboard-card__content-title">{{ $exchange['total'] }}</h3>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-voicemail"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Answer Ticket')</p>
                    <h3 class="dashboard-card__content-title">{{ $tickets['answer'] }}</h3>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-reply"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Reply Ticket')</p>
                    <h3 class="dashboard-card__content-title">{{ $tickets['reply'] }}</h3>
                </div>
            </div>
            <div class="dashboard-card">
                <div class="dashboard-card__icon"><i class="la la-money-check-alt"></i></div>
                <div class="dashboard-card__content">
                    <p class="dashboard-card__content-info">@lang('Current Balance')</p>
                    <h3 class="dashboard-card__content-title">{{ showAmount($exchange['current_balance']) }}</h3>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h5 class="title mb-3 mt-2">@lang('Your Latest Exchanges')</h5>
            <div class="card custom--card">
                <div class="card-body p-0">
                    @if (!@$latestExchange->isEmpty())
                        <table class="table table--responsive--lg">
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
                                @foreach ($latestExchange as $exchange)
                                    <tr>
                                        <td>{{ @$exchange->exchange_id }}</td>
                                        <td>
                                            <div class="table-content">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}"
                                                         class="thumb">
                                                </div>
                                                <span>{{ __(@$exchange->sendCurrency->name) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="table-content">
                                                <div class="thumb">
                                                    <img
                                                         src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}">
                                                </div>
                                                <span>{{ __(@$exchange->receivedCurrency->name) }}</span>
                                            </div>
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
                                        <td>
                                            <span class="d-block">{{ showDateTime(@$exchange->created_at) }}</span>
                                            <span class="text--base">{{ diffForHumans(@$exchange->created_at) }}</span>
                                        </td>
                                        <td>
                                            @php echo $exchange->badgeData(); @endphp
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
                        @include($activeTemplate . 'partials.empty', [
                            'message' => 'No latest exchange found',
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
        <div class="modal fade" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __($user->kyc_rejection_reason) }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
