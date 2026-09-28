@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        @if (request()->routeIs('admin.withdraw.data.all') || request()->routeIs('admin.withdraw.method'))
            <div class="col-12">
                @include('admin.withdraw.widget')
            </div>
        @endif
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Gateway | Transaction')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Send Amount')</th>
                                    <th>@lang('Conversion')</th>
                                    <th>@lang('After Charge')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $withdraw)
                                    @php
                                        $details = $withdraw->withdraw_information != null ? json_encode($withdraw->withdraw_information) : null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-bold">
                                                <a href="{{ appendQuery('method', @$withdraw->method->id) }}">
                                                    {{ __(@$withdraw->method->name) }}
                                                </a>
                                            </span>
                                            <br>
                                            <small>{{ $withdraw->trx }}</small>
                                        </td>
                                        <td>
                                            {{ showDateTime($withdraw->created_at) }} <br>
                                            {{ diffForHumans($withdraw->created_at) }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $withdraw->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ appendQuery('search', @$withdraw->user->username) }}">
                                                    <span>@</span>{{ $withdraw->user->username }}
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ showAmount($withdraw->amount) }}</span>
                                        </td>
                                        <td>
                                            1 {{ __($withdraw->method->cur_sym) }} = {{ showAmount($withdraw->rate) }}
                                            <br>
                                            <strong>
                                                {{ number_format($withdraw->final_amount, $withdraw->method->show_number_after_decimal) }}
                                                {{ __($withdraw->method->cur_sym) }}
                                            </strong>
                                        </td>
                                        <td>
                                            {{ number_format($withdraw->final_amount, $withdraw->method->show_number_after_decimal) }}
                                            {{ __($withdraw->method->cur_sym) }} -
                                            <span class="text--danger" title="@lang('charge')">
                                                {{ number_format($withdraw->charge, $withdraw->method->show_number_after_decimal) }}
                                                {{ __($withdraw->method->cur_sym) }}
                                            </span>
                                            <br>
                                            <strong title="@lang('Amount after charge')">
                                                {{ number_format($withdraw->after_charge, $withdraw->method->show_number_after_decimal) }}
                                                {{ __($withdraw->method->cur_sym) }}
                                            </strong>
                                        </td>
                                        <td>@php echo $withdraw->statusBadge @endphp</td>
                                        <td>
                                            <a href="{{ route('admin.withdraw.data.details', $withdraw->id) }}"
                                               class="btn btn-sm btn-outline--primary ms-1">
                                                <i class="la la-desktop"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($withdrawals->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($withdrawals) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' placeholder='Username / TRX' />
@endpush
