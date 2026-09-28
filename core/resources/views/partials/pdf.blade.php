<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ gs('site_name') }} - {{ __($pageTitle) }}</title>
    <link rel="icon" href="{{ siteFavicon() }}" sizes="16x16" type="image/png">

</head>
<style>
    @page {
        size: 8.27in 11.7in;
        margin: .5in;
    }

    body {
        font-family: "Arial", sans-serif;
        font-size: 14px;
        line-height: 1.5;
        color: #023047;
    }

    .body-left {
        width: 48%;
        position: relative;
    }

    .body-left::after {
        content: '';
        position: absolute;
        top: 40px;
        right: -5%;
        width: 1px;
        height: 180px;
        background: rgba(0, 0, 0, .125);
    }

    .body-right {
        width: 48%;
    }

    .shrink-text {
        max-width: 10px;
    }

    .exchange-list li {
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .exchange-list li:last-child {
        border-bottom: none;
    }

    /* Typography */
    .strong {
        font-weight: 700;
    }

    .fw-md {
        font-weight: 500;
    }

    .primary-text {
        color: #<?php echo gs('base_color'); ?>;
    }

    .text-danger {
        color: #fb4646;
    }

    .exchange-card {
        padding: 8px 0;
    }

    .exchange-card__content {
        display: inline-block;
        width: 49%;
    }

    .exchange-card__full-width {
        display: block;
        width: 100%;
    }

    h1,
    .h1 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 67px;
        line-height: 1.2;
        font-weight: 500;
    }

    h2,
    .h2 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 50px;
        line-height: 1.2;
        font-weight: 500;
    }

    h3,
    .h3 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 38px;
        line-height: 1.2;
        font-weight: 500;
    }

    h4,
    .h4 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 28px;
        line-height: 1.2;
        font-weight: 500;
    }

    h5,
    .h5 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 20px;
        line-height: 1.2;
        font-weight: 500;
    }

    h6,
    .h6 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 16px;
        line-height: 1.2;
        font-weight: 500;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .text-end {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    /* List Style */
    ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    /* Utilities */
    .d-block {
        display: block;
    }

    .mt-0 {
        margin-top: 0;
    }

    .m-0 {
        margin: 0;
    }

    .mt-3 {
        margin-top: 16px;
    }

    .mt-4 {
        margin-top: 24px;
    }

    .mb-3 {
        margin-bottom: 16px;
    }

    /* Title */
    .title {
        display: inline-block;
        letter-spacing: 0.05em;
    }

    /* Table Style */
    table {
        width: 7.27in;
        caption-side: bottom;
        border-collapse: collapse;
        border: 1px solid #eafbff;
        color: #023047;
        vertical-align: top;
    }

    table td {
        padding: 5px 15px;
    }

    table th {
        padding: 5px 15px;
    }

    table th:last-child {
        text-align: right !important;
    }

    .table> :not(caption)>*>* {
        padding: 12px 24px;
        background-color: #023047;
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px #023047;
    }

    .table>tbody {
        vertical-align: inherit;
        border: 1px solid #eafbff;
    }

    .table>thead {
        vertical-align: bottom;
        background: #<?php echo gs('base_color'); ?>;
        color: white;
    }

    .table>thead th {
        font-family: "Arial", sans-serif;
        text-align: left;
        font-size: 16px;
        letter-spacing: 0.03em;
        font-weight: 500;
    }

    .table td:last-child {
        text-align: right;
    }

    .table th:last-child {
        text-align: right;
    }

    .table> :not(:first-child) {
        border-top: 0;
    }

    .table-sm> :not(caption)>*>* {
        padding: 5px;
    }

    .table-bordered> :not(caption)>* {
        border-width: 1px 0;
    }

    .table-bordered> :not(caption)>*>* {
        border-width: 0 1px;
    }

    .table-borderless> :not(caption)>*>* {
        border-bottom-width: 0;
    }

    .table-borderless> :not(:first-child) {
        border-top-width: 0;
    }

    .table-striped>tbody>tr:nth-of-type(even)>* {
        background: #eafbff;
    }

    /* Logo */
    .logo {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 200px;
        height: 50px;
        font-size: 24px;
        text-transform: capitalize;
    }

    .logo-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .info {
        display: flex;
        justify-content: space-between;
        padding-top: 15px;
        padding-bottom: 15px;
        border-top: 1px solid #023047;
        border-bottom: 1px solid #023047;
    }

    .address {
        padding-top: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #023047;
    }

    header {
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .body {
        padding-top: 30px;
        padding-bottom: 30px;
    }

    footer {
        padding-bottom: 15px;
    }

    .badge {
        display: inline-block;
        padding: 5px 15px;
        font-size: 12px;
        line-height: 1;
        border-radius: 15px;
    }

    .badge--success {
        color: white;
        background: #02c39a;
    }

    .badge--warning {
        color: white;
        background: #ffb703;
    }

    .badge--danger {
        color: white;
        background: #fb4646;
    }

    .align-items-center {
        align-items: center;
    }

    .footer-link {
        text-decoration: none;
        color: #<?php echo gs('base_color'); ?>;
    }

    .footer-link:hover {
        text-decoration: none;
        color: #<?php echo gs('base_color'); ?>;
    }

    .list--row {
        overflow: auto
    }

    .list--row::after {
        content: '';
        display: block;
        clear: both;
    }

    .clearfix::after {
        content: '';
        display: block;
        clear: both;
    }

    .float-left {
        float: left;
    }

    .float-right {
        float: right;
    }

    .d-block {
        display: block;
    }

    .d-inline-block {
        display: inline-block;
    }

    .hr {
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        border-left: nonne;
        border-top: nonne;
        border-right: nonne;
    }

    .mt-5 {
        margin-top: 35px;
    }

    .exchanger-header {
        padding: 10px 15px;
        background: #f2f2fd;
        margin-bottom: 30px;
    }

    .exchange-footer {
        padding: 10px 15px;
        background: #f2f2fd;
        margin-top: 30px;
    }

    .mt-2 {
        margin-top: .5rem;
    }

    .mt-3 {
        margin-top: .8rem;
    }

    .mw-50 {
        max-width: 50%;
    }
</style>

<body>

    <header>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="list--row">
                        <div class="float-left mw-50">
                            <div class="logo">
                                <img src="{{ siteLogo() }}" alt="image" class="logo-img" />
                            </div>
                            <div class="mt-3">
                                <ul class="text-left">
                                    <li>
                                        <span class="d-inline-block strong">@lang('Exchange ID:')</span>
                                        <span class="d-inline-block">{{ $exchange->exchange_id }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="address-form float-right">
                            <ul class="text-end">
                                <li>
                                    <span class="d-inline-block strong">@lang('Name:')</span>
                                    <span class="d-inline-block">{{ __(@$user->fullname) }}</span>
                                </li>
                                <li>
                                    <span class="d-inline-block strong">@lang('Email:')</span>
                                    <span class="d-inline-block">{{ @$user->email }}</span>
                                </li>
                                <li>
                                    <span class="d-inline-block strong">@lang('Phone No:')</span>
                                    <span class="d-inline-block">{{ @$user->mobile }}</span>
                                </li>
                                <li>
                                    <span class="d-inline-block strong">@lang('Address:')</span>
                                    <span class="d-inline-block ">
                                        @if (@$user->address->address)
                                            {{ @$user->address->address }},
                                        @endif
                                        @if (@$user->address->city)
                                            {{ @$user->address->city }},
                                        @endif
                                        @if (@$user->address->country)
                                            {{ @$user->address->country }}
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="body clearfix">
                        <div class="text-center exchanger-header">
                            <div class="title-inset">
                                <h5 class="title m-0 text-uppercase">@lang('Exchange Information')</h5>
                            </div>
                        </div>
                        <div class="body-left float-left">
                            <h6 class="title primary-text mt-0 mb-2 text-uppercase">@lang('Sending Details')</h6>
                            <ul class="list exchange-list">
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Sending Method')</span>
                                        <span
                                            class="exchange-card__content text-end">{{ __(@$exchange->sendCurrency->name) }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Sending Currency')</span>
                                        <span class="exchange-card__content text-end">
                                            {{ __(@$exchange->sendCurrency->cur_sym) }}
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Sending Amount')</span>
                                        <span class="exchange-card__content text-end">
                                            <span>{{ number_format(@$exchange->sending_amount, $exchange->sendCurrency->show_number_after_decimal) }}</span>
                                            <span>{{ __(@$exchange->sendCurrency->cur_sym) }}</span>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Sending Charge')</span>
                                        <span class="exchange-card__content text-end">
                                            <span class="text-danger">
                                                {{ number_format(@$exchange->sending_charge,  $exchange->sendCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->sendCurrency->cur_sym) }}
                                            </span>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Total Sending Amount')</span>
                                        <span class="exchange-card__content text-end">
                                            {{ number_format(@$exchange->sending_amount + @$exchange->sending_charge, $exchange->sendCurrency->show_number_after_decimal) }}
                                            {{ __(@$exchange->sendCurrency->cur_sym) }}
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="body-right float-right">
                            <h6 class="title primary-text mt-0 mb-2 text-uppercase">@lang('Receiving Details')</h6>
                            <ul class="list exchange-list">
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content"> @lang('Receving Method')</span>
                                        <span
                                            class="exchange-card__content text-end">{{ __(@$exchange->receivedCurrency->name) }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Receving Currency')</span>
                                        <span class="exchange-card__content text-end">
                                            {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Receving Amount')</span>
                                        <span class="exchange-card__content text-end">
                                            {{ number_format(@$exchange->receiving_amount, $exchange->receivedCurrency->show_number_after_decimal) }}
                                            {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Receving charge')</span>
                                        <span class="exchange-card__content text-end">
                                            <span class="text-danger">
                                                {{ number_format(@$exchange->receiving_charge, $exchange->receivedCurrency->show_number_after_decimal) }}
                                                {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                            </span>
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Total Receving Amout')</span>
                                        <span class="exchange-card__content text-end">
                                            {{ number_format($exchange->receiving_amount - @$exchange->receiving_charge, $exchange->receivedCurrency->show_number_after_decimal) }}
                                            {{ __(@$exchange->receivedCurrency->cur_sym) }}
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="exchange-card__full-width mt-5 clearfix">
                        <ul class="list exchange-list">
                            <li>
                                <div class="exchange-card">
                                    <span class="exchange-card__content">
                                        @lang('Your')
                                        <span>{{ __(@$exchange->receivedCurrency->name) }}</span>
                                        @lang('Wallet ID/Number')
                                    </span>
                                    <span class="exchange-card__content text-end">{{ $exchange->wallet_id }}</span>
                                </div>
                            </li>
                            @if ($exchange->status == Status::EXCHANGE_APPROVED)
                                <li>
                                    <div class="exchange-card">
                                        <span class="exchange-card__content">@lang('Admin Transaction/Wallet Number')</span>
                                        <span class="exchange-card__content text-end mt-1">
                                            {{ $exchange->admin_trx_no }}
                                        </span>
                                    </div>
                                </li>
                            @endif
                            <li>
                                <div class="exchange-card">
                                    <span class="exchange-card__content">@lang('Status')</span>
                                    <span class="exchange-card__content text-end">
                                        @php echo $exchange->badgeData(false) @endphp
                                    </span>
                                </div>
                            </li>
                            <li>
                                <div class="exchange-card">
                                    <span class="exchange-card__content">@lang('Exchange Time')</span>
                                    <span
                                        class="exchange-card__content text-end">{{ showDateTime($exchange->created_at) }}</span>
                                </div>
                            </li>
                            @if ($exchange->admin_feedback != null)
                                <li>
                                    <div class="exchange-card">
                                        @if ($exchange->status == Status::EXCHANGE_CANCEL)
                                            <span class="exchange-card__content text-danger">@lang('Failed Reason')</span>
                                        @else
                                            <span class="exchange-card__content">@lang('Admin Feedback')</span>
                                        @endif
                                        <span
                                            class="exchange-card__content text-end">{{ __($exchange->admin_feedback) }}</span>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="exchange-footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <span class="d-block text-center">
                        @lang('Powered By')
                        <a href="{{ route('home') }}" class="footer-link">{{ __(gs('site_name')) }}</a>
                    </span>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
