@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">

        <div class="show-filter mb-3 text-end">
            <button type="button" class="btn btn--base showFilterBtn btn-sm"><i class="las la-filter"></i> @lang('Filter')</button>
        </div>
        <div class="card custom--card responsive-filter-card mb-4">
            <div class="card-body">
                <form>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="flex-grow-1">
                            <label class="form-label">@lang('Transaction Number')</label>
                            <input type="search" name="search" value="{{ request()->search }}" class="form-control form--control">
                        </div>
                        <div class="flex-grow-1 select2-parent">
                            <label class="form-label d-block">@lang('Type')</label>
                            <select name="trx_type" class="form-select form--control select2-basic" data-minimum-results-for-search="-1">
                                <option value="">@lang('All')</option>
                                <option value="+" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                                <option value="-" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                            </select>
                        </div>
                        <div class="flex-grow-1 select2-parent">
                            <label class="form-label d-block">@lang('Remark')</label>
                            <select class="form-select form--control select2-basic" data-minimum-results-for-search="-1" name="remark">
                                <option value="">@lang('All')</option>
                                @foreach ($remarks as $remark)
                                    <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--base w-100"><i class="las la-filter"></i> @lang('Filter')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card custom--card">
            <div class="card-body p-0">
                @if (!$transactions->isEmpty())
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>@lang('Trx')</th>
                                <th>@lang('Transacted')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Post Balance')</th>
                                <th>@lang('Detail')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $trx)
                                <tr>
                                    <td>
                                        <strong>{{ $trx->trx }}</strong>
                                    </td>

                                    <td>
                                        {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                    </td>

                                    <td>
                                        <span class="fw-bold @if ($trx->trx_type == '+') text--success @else text--danger @endif">
                                            {{ $trx->trx_type }} {{ showAmount($trx->amount) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ showAmount($trx->post_balance) }}
                                    </td>


                                    <td>{{ __($trx->details) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @include($activeTemplate . 'partials.empty', ['message' => 'No Transaction Found!'])
                @endif
            </div>
        </div>
        @if ($transactions->hasPages())
            <div class="mt-3">
                {{ paginateLinks($transactions) }}
            </div>
        @endif
    </div>
@endsection

@push('style')
    <style>
        .select2-container:has(.select2-selection--single) {
            width: 100% !important;
        }
    </style>
@endpush


@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.select2-basic').select2({
                dropdownParent: $('.responsive-filter-card')
            });

        })(jQuery);
    </script>
@endpush
