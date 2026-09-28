@extends($activeTemplate . 'layouts.master')
@php
    $kycContent = getContent('kyc_content.content', true);
@endphp
@section('content')
    <div class="container">
        <div class="notice"></div>
        <div class="row justify-content-center gy-4">
            @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
                <div class="col-12">
                    <div class="alert alert--danger" role="alert">
                        <div class="alert__icon"><i class="fas fa-file-signature"></i></div>
                        <p class="alert__message m-0">
                            <span class="fw-bold">@lang('KYC Documents Rejected')</span><br>
                            <small>
                                <i>
                                    {{ __(@$kycContent->data_values->rejected_content) }}
                                    <a class="link-color" data-bs-toggle="modal" data-bs-target="#kycRejectionReason"
                                       href="javascript::void(0)">
                                        @lang('Click here')
                                    </a>
                                    @lang('to show the reason').<br>
                                    <a class="link-color" href="{{ route('user.kyc.form') }}">
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
                                    <a class="link-color" href="{{ route('user.kyc.form') }}">
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
                                    <a class="link-color" href="{{ route('user.kyc.data') }}">
                                        @lang('Click here')
                                    </a>
                                    @lang('to see your submitted information')
                                </i>
                            </small>
                        </p>
                    </div>
                </div>
            @endif
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-sync"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Approved Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ $exchange['approved'] }}</h4>
                        <a href="{{ route('user.exchange.list', 'approved') }}" class="btn--simple">
                            @lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-undo-alt"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Pending Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ $exchange['pending'] }}</h4>
                        <a href="{{ route('user.exchange.list', 'pending') }}" class="btn--simple">
                            @lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-window-close"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Canceled Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ $exchange['cancel'] }}</h4>
                        <a href="{{ route('user.exchange.list', 'canceled') }}" class="btn--simple">
                            @lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-sync-alt"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Refunded Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ $exchange['refunded'] }}</h4>
                        <a href="{{ route('user.exchange.list', 'refunded') }}" class="btn--simple">
                            @lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-exchange-alt"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Total Exchange')</h5>
                        <h4 class="widget-item__amount ">{{ $exchange['total'] }}</h4>
                        <a href="{{ route('user.exchange.list', 'list') }}" class="btn--simple">
                            @lang('View All')
                            <span class="icon text--base"> <i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fa-solid fa-list-ul"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Transactions')</h5>
                        <h4 class="widget-item__amount ">{{ $totalTransaction }}</h4>
                        <a href="{{ route('user.transactions') }}" class="btn--simple">
                            @lang('View All')
                            <span class="icon text--base"> <i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item h-100">
                    <div class="widget-item__icon"><i class="fas fa-voicemail"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Answer Ticket')</h5>
                        <h4 class="widget-item__amount ">{{ $tickets['answer'] }}</h4>
                        <a href="{{ route('ticket.index') }}" class="btn--simple">
                            @lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-reply"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Reply Ticket')</h5>
                        <h4 class="widget-item__amount ">{{ $tickets['reply'] }}</h4>
                        <a href="{{ route('ticket.index') }}" class="btn--simple">
                            @lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="widget-item">
                    <div class="widget-item__icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="widget-item__content">
                        <h5 class="widget-item__title">@lang('Your Balance')</h5>
                        <h4 class="widget-item__amount ">{{ showAmount($exchange['current_balance']) }}</h4>
                        <a href="{{ route('user.report.commission.log') }}" class="btn--simple">
                            @lang('View All')
                            <span class="icon text--base"><i class="fas fa-angle-double-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-5">
                <h5 class="title mb-2">@lang('Your Latest Exchanges')</h5>
                <div class="card custom--card">
                    @if (!$latestExchange->isEmpty())
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
                                    @foreach ($latestExchange as $exchange)
                                        <tr>
                                            <td>{{ @$exchange->exchange_id }}</td>
                                            <td>
                                                <span class="thumb">
                                                    <img class="table-currency-img"
                                                         src="{{ getImage(getFilePath('currency') . '/' . @$exchange->sendCurrency->image, getFileSize('currency')) }}">
                                                </span>
                                                {{ $exchange->sendCurrency->name }}
                                            </td>
                                            <td>
                                                <span class="thumb">
                                                    <img src="{{ getImage(getFilePath('currency') . '/' . @$exchange->receivedCurrency->image, getFileSize('currency')) }}"
                                                         class="table-currency-img">
                                                </span>
                                                {{ __($exchange->receivedCurrency->name) }}
                                            </td>
                                            <td>
                                                {{ number_format($exchange->sending_amount,  $exchange->sendCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                                                <i class="la la-arrow-right text--base"></i>
                                                {{ number_format($exchange->receiving_amount, $exchange->receivedCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                            </td>
                                            <td>
                                                <span class="d-block">{{ showDateTime(@$exchange->created_at) }}</span>
                                                <span
                                                      class="text--base">{{ diffForHumans(@$exchange->created_at) }}</span>
                                            </td>
                                            <td> @php echo $exchange->badgeData(); @endphp </td>
                                            <td>

                                                <a href="{{ route('user.exchange.details', $exchange->exchange_id) }}"
                                                   class="btn btn--base-outline btn-sm" data-reason="{{ $exchange->cancle_reason }}">
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
                            'message' => 'No Latest Exchange Found!',
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
