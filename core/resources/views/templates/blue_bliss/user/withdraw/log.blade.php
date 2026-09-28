@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row mb-2 justify-content-end gy-4">
            <div class="col-lg-4">
                <form>
                    <div class="input-group">
                        <input name="search" value="{{ request()->search ?? '' }}" type="text" class="form--control form-control bg-white"
                            placeholder="@lang('Search by Transaction ID')">
                        <button type="submit" class="btn btn--base input-group-text"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="card custom--card">
                    @if (!$withdraws->isEmpty())
                        <div class="card-body p-0">
                            <table class="table custom--table table-responsive--md">
                                <thead>
                                    <tr>
                                        <th>@lang('Transaction ID')</th>
                                        <th>@lang('Receiving Method')</th>
                                        <th>@lang('Send Amount')</th>
                                        <th>@lang('Rate')</th>
                                        <th>@lang('Receivable')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($withdraws as $withdraw)
                                        <tr>
                                            <td>{{ __($withdraw->trx) }}</td>
                                            <td>{{ __($withdraw->method->name) }}</td>
                                            <td>{{ showAmount($withdraw->amount) }}</td>
                                            <td>
                                                <span>
                                                    1 {{ $withdraw->method->cur_sym }} =
                                                    {{ showAmount($withdraw->rate) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ number_format($withdraw->after_charge, $withdraw->method->show_number_after_decimal) }}
                                                {{ __($withdraw->method->cur_sym) }}
                                            </td>
                                            <td>@php echo $withdraw->statusBadge @endphp </td>
                                            <td>
                                                <button class="btn btn--base-outline btn-sm detailBtn" data-withdraw="{{ $withdraw }}"
                                                    data-date="{{ __(showDateTime($withdraw->created_at)) }}" data-status="{{ $withdraw->statusBadge }}">
                                                    <i class="fas fa-desktop"></i>
                                                    @lang('Details')
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @include($activeTemplate . 'partials.empty', [
                            'message' => 'No withdraw log found',
                        ])
                    @endif
                </div>
                @if ($withdraws->hasPages())
                    <div class="py-3 custom__paginate">
                        {{ paginateLinks($withdraws) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal trackModal" id="detailModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-center"> @lang('Withdraw Details')</h4>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body p-0">
                    <div class="info-box">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="list-group custom--list-group list-group-flush">
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Status')</span>
                                        <span class="fw-bold status"></span>
                                    </li>
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Transaction ID')</span>
                                        <span class="fw-bold trxId text--base"></span>
                                    </li>
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Withdraw Method')</span>
                                        <span class="fw-bold withdrawMethod"></span>
                                    </li>
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Method Currency')</span>
                                        <span class="fw-bold withdrawCurrency"></span>
                                    </li>
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Send Amount')</span>
                                        <span class="fw-bold send_amount"></span>
                                    </li>
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Get Amount')</span>
                                        <span class="fw-bold get_amount"></span>
                                    </li>
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Rate')</span>
                                        <span class="fw-bold rate"></span>
                                    </li>
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Charge')</span>
                                        <span class="fw-bold text--danger charge"></span>
                                    </li>
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Receive Amount After Charge')</span>
                                        <span class="fw-bold afterChargeReceiveAmount"></span>
                                    </li>
                                    <li class="list-group-item ps-0 d-flex justify-content-between flex-wrap border-dotted">
                                        <span class="text-muted">@lang('Time')</span>
                                        <span class="fw-bold time"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.detailBtn').on('click', function() {
                let modal = $('#detailModal');
                let withdraw = $(this).data('withdraw');
                let siteCurrency = '{{ __(gs('cur_text')) }}';

                modal.find('.trxId').text(withdraw.trx)
                modal.find('.withdrawMethod').text(withdraw.method.name);
                modal.find('.withdrawCurrency').text(withdraw.method.cur_sym);
                modal.find('.send_amount').text(`${parseFloat(withdraw.amount).toFixed(2)} ${siteCurrency}`);
                modal.find('.get_amount').text(
                    `${parseFloat(withdraw.final_amount).toFixed(withdraw.method.show_number_after_decimal)} ${withdraw.method.cur_sym}`);
                modal.find('.withdrawCurrency').text(withdraw.method.cur_sym);
                modal.find('.rate').text(
                    `1 ${withdraw.method.cur_sym} = ${parseFloat(withdraw.rate).toFixed(2)} ${siteCurrency}`
                );
                modal.find('.charge').text(
                    `${parseFloat(withdraw.charge).toFixed(withdraw.method.show_number_after_decimal)} ${withdraw.method.cur_sym}`);
                modal.find('.afterChargeReceiveAmount').text(
                    `${parseFloat(withdraw.after_charge).toFixed(withdraw.method.show_number_after_decimal)} ${withdraw.method.cur_sym}`);
                modal.find('.time').text($(this).data('date'));
                modal.find('.status').html($(this).data('status'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
