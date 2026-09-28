@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Exchange ID')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Received Method')</th>
                                    <th>@lang('Send Amount')</th>
                                    <th>@lang('Send Method')</th>
                                    <th>@lang('Received Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exchanges as $exchange)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $exchange->exchange_id }}</span>
                                            <br>
                                            <small class="text-muted">{{ showDateTime($exchange->created_at) }}</small>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ __(@$exchange->user->fullname) }}</span>
                                            <span>
                                                <a class="text--primary"
                                                   href="{{ route('admin.users.detail', @$exchange->user_id) }}">
                                                    <span class="text--primary">@</span>{{ __(@$exchange->user->username) }}
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ __(@$exchange->sendCurrency->name) }}</span>
                                            <span class="text--primary">{{ __(@$exchange->sendCurrency->cur_sym) }}</span>
                                        </td>
                                        <td>
                                            <span class="d-block">
                                                {{ number_format($exchange->sending_amount, $exchange->sendCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                                            </span>
                                            <span>
                                                {{ number_format($exchange->sending_amount, $exchange->sendCurrency->show_number_after_decimal) }}
                                            </span>
                                            +
                                            <span class="text--danger">
                                                {{ number_format($exchange->sending_charge, $exchange->sendCurrency->show_number_after_decimal) }}
                                            </span>
                                            =
                                            <span>
                                                {{ number_format($exchange->sending_amount + $exchange->sending_charge, $exchange->sendCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ __(@$exchange->receivedCurrency->name) }}</span>
                                            <span
                                                  class="text--primary">{{ __(@$exchange->receivedCurrency->cur_sym) }}</span>
                                        </td>
                                        <td>
                                            <span class="d-block">
                                                {{ number_format($exchange->receiving_amount, $exchange->receivedCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                            </span>
                                            <span>
                                                {{ number_format($exchange->receiving_amount, $exchange->receivedCurrency->show_number_after_decimal) }}
                                            </span>
                                            -
                                            <span class="text--danger">
                                                {{ number_format($exchange->receiving_charge, $exchange->receivedCurrency->show_number_after_decimal) }}
                                            </span>
                                            =
                                            <span>
                                                {{ number_format($exchange->receiving_amount - $exchange->receiving_charge, $exchange->receivedCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                            </span>
                                        </td>
                                        <td> @php echo $exchange->badgeData() @endphp </td>
                                        <td>
                                            <a href="{{ route('admin.exchange.details', $exchange->id) }}"
                                               class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i>@lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-muted text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($exchanges->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($exchanges) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- export modal --}}
    <div class="modal fade" id="exportModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Export Filter')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-close" aria-hidden="true"></i>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.exchange.export') }}" id="exportForm">
                    @csrf
                    <div class="modal-body">

                        <!-- Standard Export Columns -->
                        <div class="form-group">
                            <label class="fw-bold">@lang('Export Columns')</label>
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label>@lang('Exchange ID')</label>
                                    <input type="checkbox" name="columns[]" value="exchange_id" checked data-width="100%"
                                           data-size="large" data-onstyle="-success" data-offstyle="-danger"
                                           data-bs-toggle="toggle" data-height="50" data-on="@lang('Yes')"
                                           data-off="@lang('No')">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>@lang('User')</label>
                                    <input type="checkbox" name="columns[]" value="user_id" checked data-width="100%"
                                           data-size="large" data-onstyle="-success" data-offstyle="-danger"
                                           data-bs-toggle="toggle" data-height="50" data-on="@lang('Yes')"
                                           data-off="@lang('No')">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>@lang('Received Method')</label>
                                    <input type="checkbox" name="columns[]" value="receive_currency_id" checked
                                           data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger"
                                           data-bs-toggle="toggle" data-height="50" data-on="@lang('Yes')"
                                           data-off="@lang('No')">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>@lang('Received Amount')</label>
                                    <input type="checkbox" name="columns[]" value="receiving_amount" checked
                                           data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger"
                                           data-bs-toggle="toggle" data-height="50" data-on="@lang('Yes')"
                                           data-off="@lang('No')">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>@lang('Send Method')</label>
                                    <input type="checkbox" name="columns[]" value="send_currency_id" checked
                                           data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger"
                                           data-bs-toggle="toggle" data-height="50" data-on="@lang('Yes')"
                                           data-off="@lang('No')">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>@lang('Send Amount')</label>
                                    <input type="checkbox" name="columns[]" value="sending_amount" checked
                                           data-width="100%" data-size="large" data-onstyle="-success"
                                           data-offstyle="-danger" data-bs-toggle="toggle" data-height="50"
                                           data-on="@lang('Yes')" data-off="@lang('No')">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>@lang('Status')</label>
                                    <input type="checkbox" name="columns[]" value="status" checked data-width="100%"
                                           data-size="large" data-onstyle="-success" data-offstyle="-danger"
                                           data-bs-toggle="toggle" data-height="50" data-on="@lang('Yes')"
                                           data-off="@lang('No')">
                                </div>
                            </div>
                        </div>

                        <!-- Order By -->
                        <div class="form-group">
                            <label class="fw-bold">@lang('Order By')</label>
                            <input type="hidden" name="order_by" value="desc"> <!-- Default value -->
                            <input type="checkbox" id="orderByCheckbox" data-width="100%" data-size="large"
                                   data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50"
                                   data-on="@lang('ASC')" data-off="@lang('DESC')" onchange="toggleOrderBy()">
                        </div>

                        <!-- Export Item Selection -->
                        <div class="form-group">
                            <label class="fw-bold">@lang('Export Item')</label>
                            <select class="form-control form-control-lg" name="export_item">
                                <option value="10">@lang('10')</option>
                                <option value="50">@lang('50')</option>
                                <option value="100">@lang('100')</option>
                                @if ($exchanges->total() > 100)
                                    <option value="{{ $exchanges->total() }}">{{ $exchanges->total() }}
                                        @lang('Exchanges')</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="scope" value="{{ $scope }}">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Export')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Exchange ID, username" dateSearch='yes' />
    <button type="button" class="btn  btn-outline--warning h-45 exportBtn">
        <i class="las la-cloud-download-alt"></i> @lang('Export')
    </button>
@endpush

@push('script')
    <script>
        "use strict";

        function toggleOrderBy() {
            let checkbox = document.getElementById('orderByCheckbox');
            let orderByInput = document.querySelector('input[name="order_by"]');
            orderByInput.value = checkbox.checked ? 'asc' : 'desc';
        }

        (function($) {
            $('.exportBtn').on('click', function() {
                $('#exportModal').modal('show');
            });
        })(jQuery);
    </script>
@endpush
