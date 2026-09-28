@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4 justify-content-center">
        <div class="col-xl-4 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Sent by user')</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>{{ __(@$exchange->sendCurrency->name) }}</h5>
                            <small class="text-muted"> @lang('Payment Method')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>{{ __(@$exchange->sendCurrency->cur_sym) }}</h5>
                            <small class="text-muted"> @lang('Received Currency')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>
                                {{ number_format($exchange->sending_amount, @$exchange->sendCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                            </h5>
                            <small class="text-muted"> @lang('Amount')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>
                                {{ number_format(@$exchange->charge->sending_charge->percent_amount, @$exchange->sendCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                                <small class="text--small">
                                    ({{ getAmount(@$exchange->charge->sending_charge->percent_charge) }}%)
                                </small>
                            </h5>
                            <small class="text-muted"> @lang('Percent Charge')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>
                                {{ number_format(@$exchange->charge->sending_charge->fixed_charge, @$exchange->sendCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                            </h5>
                            <small class="text-muted"> @lang('Fixed Charge')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5 class="text--danger">
                                {{ number_format($exchange->sending_charge, @$exchange->sendCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                            </h5>
                            <small class="text-muted"> @lang('Total Charge')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>
                                {{ number_format($exchange->sending_charge + $exchange->sending_amount, @$exchange->sendCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                            </h5>
                            <small class="text-muted"> @lang('Total Amount Sent By User')</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-12">
            <div class="card ">
                <div class="card-header">
                    <h5 class="card-title">@lang('Receivable for User')</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>{{ __(@$exchange->receivedCurrency->name) }}</h5>
                            <small class="text-muted"> @lang('Payment Method')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>{{ __(@$exchange->receivedCurrency->cur_sym) }}</h5>
                            <small class="text-muted"> @lang('Currency')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>
                                {{ number_format($exchange->receiving_amount, @$exchange->receivedCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                            </h5>
                            <small class="text-muted"> @lang('Amount')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>
                                {{ number_format(@$exchange->charge->receiving_charge->percent_amount, @$exchange->receivedCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                <small class="text--small">
                                    ({{ getAmount(@$exchange->charge->receiving_charge->percent_charge) }}%)
                                </small>
                            </h5>
                            <small class="text-muted"> @lang('Percent Charge')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>
                                {{ number_format(@$exchange->charge->receiving_charge->fixed_charge, @$exchange->receivedCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                            </h5>
                            <small class="text-muted"> @lang('Fixed Charge')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5 class="text--danger">
                                {{ number_format($exchange->receiving_charge, @$exchange->receivedCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                            </h5>
                            <small class="text-muted"> @lang('Total Charge')</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap flex-column">
                            <h5>
                                {{ number_format($exchange->receiving_amount - $exchange->receiving_charge, @$exchange->receivedCurrency->show_number_after_decimal) }}
                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                            </h5>
                            <small class="text-muted"> @lang('Receivable Amount for User')</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-sm-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="card-title">@lang('Exchange Information')</h5>
                    @if ($exchange->status == Status::EXCHANGE_PENDING)
                        <div class="d-flex flex-wrap justify-content-end mb-3 gap-2">
                            <button class="btn btn-outline--success btn-approved flex-grow-1" type="button">
                                <i class="fas fa-check"></i>
                                @lang('Approve')
                            </button>
                            <button type="button" class="btn-outline--danger btn btn-cancel flex-grow-1" type="button">
                                <i class="fas fa-times"></i>
                                @lang('Cancel')
                            </button>
                            <button type="button" class="btn btn-outline--warning btn-refund flex-grow-1" type="button">
                                <i class="fas fa-undo"></i>
                                @lang('Refund')
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold"> @lang('Exchange ID')</span>
                            <span><strong>{{ $exchange->exchange_id }}</strong></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold"> @lang('User Name')</span>
                            <span>
                                <a class="text--primary" href="{{ route('admin.users.detail', $exchange->user_id) }}">
                                    <span class="text--primary">@</span>{{ __(@$exchange->user->username) }}
                                </a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold"> @lang('Status')</span>
                            <div class="text-end">
                                @php echo $exchange->badgeData() @endphp
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold"> @lang('Automatic Payment Status')</span>
                            <div class="text-end">
                                @if ($exchange->automatic_payment_status)
                                    <span class="badge badge--success">@lang('Completed')</span>
                                @else
                                    <span class="badge badge--danger">@lang('Not Completed')</span>
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold"> @lang('Buy Rate')</span>
                            <div>
                                <span>{{ showAmount($exchange->buy_rate) }}</span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold"> @lang('Customer Wallet')</span>
                            <span class="fw-bold">{{ __(@$exchange->wallet_id) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                            <span class="fw-bold"> @lang('Exchange Time')</span>
                            <div class="text-end">
                                <span class="d-block">{{ showDateTime($exchange->created_at) }}</span>
                                <span> {{ diffForHumans($exchange->created_at) }}</span>
                            </div>
                        </li>
                        @if ($exchange->admin_feedback)
                            <li class="list-group-item d-flex justify-content-between flex-wrap">
                                <span class="fw-bold">
                                    @if ($exchange->status == Status::EXCHANGE_REFUND)
                                        @lang('Reason of refund')
                                    @elseif($exchange->status == Status::EXCHANGE_CANCEL)
                                        @lang('Reason of cancel')
                                    @endif
                                </span>
                                <span>{{ __($exchange->admin_feedback) }}</span>
                            </li>
                        @endif
                    </ul>
                    @if ($exchange->status == Status::EXCHANGE_APPROVED)
                        <div class="form-group alert alert-success p-3">
                            <span class="fw-bold text-dark">@lang('This exchange is paid successfully')</span>
                        </div>
                    @endif
                </div>
            </div>
            @if ($exchange->user_data != null)
                <div class="card b-radius--10 overflow-hidden box--shadow1 mt-3">
                    <div class="card-header text-center">
                        <h5>@lang('Sending Details')</h5>
                    </div>
                    <div class="card-body">
                        <x-view-form-data :data="$exchange->user_data" />
                    </div>
                </div>
            @endif
            @if ($exchange->transaction_proof_data != null)
                <div class="card b-radius--10 overflow-hidden box--shadow1 mt-3">
                    <div class="card-header">
                        <h5>@lang('Transaction Proof')</h5>
                    </div>
                    <div class="card-body">
                        <x-view-form-data :data="$exchange->transaction_proof_data" />
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div id="modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" class="disableSubmission">
                    @csrf
                    <input type="hidden" name="id" value="{{ $exchange->id }}">
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.exchange.list', 'list') }}" />
    @if (!@$exchange->deposit)
        <a href="{{ route('admin.exchange.download', $exchange->id) }}" class="btn btn-sm btn-outline--info">
            <i class="la la-download"></i>@lang('Download')
        </a>
    @endif
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            let modal = $('#modal');
            $('.btn-approved').on('click', function(e) {
                let html = `
                <div class="form-group">
                    <label for="">Transaction Number</label>
                    <input type="text" name="transaction" required class="form-control">
                </div>`;
                modal.find(".modal-body").html(html);
                modal.find('form').attr('action', `{{ route('admin.exchange.approve', $exchange->id) }}`)
                modal.find(".modal-title").text(`Approve Exchange`);
                modal.modal('show');
            });
            $('.btn-cancel').on('click', function(e) {
                let html = `
                <div class="form-group">
                    <label>Reason Of Cancel</label>
                    <textarea type="text" name="cancel_reason" required class="form-control"></textarea>
                </div>`;
                modal.find(".modal-body").html(html);
                modal.find('form').attr('action', `{{ route('admin.exchange.cancel', $exchange->id) }}`)
                modal.find(".modal-title").text(`Cancel Exchange`);
                modal.modal('show');
            });
            $('.btn-refund').on('click', function(e) {
                let html = `
                <div class="form-group">
                    <label>Reason Of Refund</label>
                    <textarea type="text" name="refund_reason" required class="form-control"></textarea>
                </div>`;
                modal.find('form').attr('action', `{{ route('admin.exchange.refund', $exchange->id) }}`)
                modal.find(".modal-body").html(html);
                modal.find(".modal-title").text(`Refund Exchange`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
@push('style')
    <style>
        .list-group-item {
            border: 1px solid rgba(140, 140, 140, 0.125)
        }
    </style>
@endpush
