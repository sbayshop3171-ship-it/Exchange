@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.users.all') }}" icon="las la-users" title="Total Users" value="{{ $widget['total_users'] }}"
                bg="primary" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.users.active') }}" icon="las la-user-check" title="Active Users"
                value="{{ $widget['verified_users'] }}" bg="success"/>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.users.email.unverified') }}" icon="lar la-envelope" title="Email Unverified Users"
                value="{{ $widget['email_unverified_users'] }}" bg="danger" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.users.mobile.unverified') }}" icon="las la-comment-slash" title="Mobile Unverified Users"
                value="{{ $widget['mobile_unverified_users'] }}" bg="warning" />
        </div>
    </div>
    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.exchange.list', 'approved') }}" title="Approved Exchanges" icon="far fa-thumbs-up"
                value="{{ @$exchange['approved'] }}" bg="success" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.exchange.list', 'pending') }}" title="Pending Exchange" icon="fas fa-retweet"
                value="{{ @$exchange['pending'] }}" bg="warning" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.exchange.list', 'refunded') }}" title="Refunded Exchanges" icon="fas fa-reply"
                value="{{ @$exchange['refund'] }}" bg="dark" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.exchange.list', 'canceled') }}" title="Canceled Exchanges" icon="fas fa-ban"
                value="{{ @$exchange['canceled'] }}" bg="danger" />
        </div>
    </div>
    @if (!blank($reserveCurrency))
        <h4 class="mt-4 mb-2"> @lang('Reserved Currencies')</h4>
        <div class="row gy-4">
            @foreach ($reserveCurrency as $currency)
                <div class="col-xxl-3">
                    <div class="widget-two box--shadow2 b-radius--5 bg--white">
                        <div class="widget-two__icon b-radius--5">
                            <img class="reserved-currency-image"
                                src="{{ getImage(getFilePath('currency') . '/' . @$currency->image, getFileSize('currency')) }}" alt="">
                        </div>
                        <div class="widget-two__content">
                            <h4 class="">
                                <span class="ms-1">
                                    {{ __($currency->name) }} -
                                    {{ __($currency->cur_sym) }}
                                </span>
                            </h4>
                            <p>
                                {{ showAmount($currency->reserve, currencyFormat: false) }}
                                {{ __($currency->cur_sym) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="row mb-none-30 mt-5">
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Browser') (@lang('Last 30 days'))</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By OS') (@lang('Last 30 days'))</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Country') (@lang('Last 30 days'))</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.cron_modal')
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary btn-sm" data-bs-toggle="modal" data-bs-target="#cronModal">
        <i class="las la-server"></i>@lang('Cron Setup')
    </button>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";

        piChart(
            document.getElementById('userBrowserChart'),
            @json(@$chart['user_browser_counter']->keys()),
            @json(@$chart['user_browser_counter']->flatten())
        );

        piChart(
            document.getElementById('userOsChart'),
            @json(@$chart['user_os_counter']->keys()),
            @json(@$chart['user_os_counter']->flatten())
        );

        piChart(
            document.getElementById('userCountryChart'),
            @json(@$chart['user_country_counter']->keys()),
            @json(@$chart['user_country_counter']->flatten())
        );
    </script>
@endpush
