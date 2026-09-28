@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            @if ($user->referrer)
                <div class="col-md-12 mb-2">
                    <h5 class="alert alert--info">@lang('You are referred by')
                        <span class="text--base">{{ @$user->referrer->fullname }}</span>
                    </h5>
                </div>
            @endif
            <div class="col-md-12">
                <div class="form-group">
                    <div class="input-group bl-0 br-0">
                        <span class="input-group-text bg--base text-white">@lang('My Referral Link')</span>
                        <input type="text" class="form-control form--control referralURL bg-white"
                               value="{{ route('home') }}?reference={{ $user->username }}" readonly>
                        <button type="button" class="input-group-text bg--base text-white" id="copyBoard">
                            <i class="fa fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="text-start"> @lang('My Referrals Users')</h5>
                    </div>
                    <div class="card-body p-3">
                        @if ($user->allReferrals->count() > 0 && $maxLevel > 0)
                            <div class="treeview-container">
                                <ul class="treeview">
                                    <li class="items-expanded"> {{ $user->fullname }} ( {{ $user->username }} )
                                        @include($activeTemplate . 'partials.under_tree', [
                                            'user' => $user,
                                            'layer' => 0,
                                            'isFirst' => true,
                                        ])
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link type="text/css" href="{{ asset('assets/global/css/jquery.treeView.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/jquery.treeView.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.treeview').treeView();
            $('#copyBoard').on('click', function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .copied::after {
            background-color: #{{ gs('base_color') }};
        }

        .form-control.form--control.referralURL.bg-white {
            background: white !important;
        }
    </style>
@endpush
