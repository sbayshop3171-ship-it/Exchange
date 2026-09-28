@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row gy-4">
            @if ($user->referrer)
                <div class="col-md-12">
                    <h5>
                        @lang('You are referred by')
                        <span class="text--base">{{ @$user->referrer->fullname }}</span>
                    </h5>
                </div>
            @endif
            <div class="col-md-12">
                <div class="input-group">
                    <span class="input-group-text bg--base text-white">@lang('Referral Link')</span>
                    <input type="text" class="form-control form--control bg-white referralURL"
                           value="{{ route('home') }}?reference={{ $user->username }}" readonly>
                    <button type="button" class="input-group-text text--base bg-white" id="copyBoard">
                        <i class="la la-copy"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="text-start"> @lang('Users Referred By Me')</h5>
                    </div>
                    <div class="card-body">
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
                        @else
                            @lang('No referee yet.')
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

        .input-group-text {
            border: 1px solid #ced4da !important;
        }
    </style>
@endpush
