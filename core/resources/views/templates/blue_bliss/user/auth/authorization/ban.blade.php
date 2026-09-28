@extends($activeTemplate . 'layouts.frontend')
@php
    $bannedContent = getContent('banned.content', true);
@endphp
@section('content')
    <section class="error-page pt-80 pb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h3 class="error-content__title mb-3 text--danger text-center">
                        {{ __(@$bannedContent->data_values->heading) }}</h3>
                    <div class="error-content text-center">
                        <div class="error-content__thumb">
                            <img src="{{ frontendImage('banned', @$bannedContent->data_values->image, '700x400') }}"
                                alt="ban image">
                        </div>
                        <div class="error-content__desc mb-3">
                            <h5 class="my-3"> @lang('Ban Reason')</h5>
                            <p class="text--danger"> {{ __(auth()->user()->ban_reason) }}</p>
                        </div>
                        <a href="{{ route('home') }}" class="btn--base btn btn--sm">@lang('Go To Home')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $('footer').remove();
            $('header').remove();
            $('.page-header').remove();
            $('#top-notice').remove();
        })(jQuery);
    </script>
@endpush
@push('style')
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-content__thumb {
            max-width: 700px;
            margin: 0 auto;
        }

        .error-content__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .error-content__title {
            font-weight: bolder;
        }

        .error-content__desc {
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endpush
