@php
    $mobileAppContent = getContent('mobile_app.content', true);
    $mobileAppElements = getContent('mobile_app.element');
@endphp

<div class="exchange-currency pt-80 pb-80">
    <div class="exchange-currency__shape">
        <img src="{{ asset($activeTemplateTrue . 'images/shape/app_bg.png') }}" alt="image">
    </div>
    <div class="container">
        <div class="exchange-currency__wrapper">
            <div class="exchange-currency__thumb">
                <img src="{{ frontendImage('mobile_app', @$mobileAppContent->data_values->mobile_image, '325x490') }}" alt="mobile-image">
            </div>
            <div class="exchange-currency__content">
                <h2 class="title">{{ __(@$mobileAppContent->data_values->heading) }}</h2>
                <p class="desc">{{ __(@$mobileAppContent->data_values->description) }}</p>

                @foreach (@$mobileAppElements as $mobileAppElement)
                    <a href="{{ $mobileAppElement->data_values->download_link }}" class="exchange-currency__link" target="_blank">
                        <img src="{{ frontendImage('mobile_app', @$mobileAppElement->data_values->app_store_image, '180x55') }}"
                             alt="app-store-image">
                    </a>
                @endforeach
                <p class="text">{{ __(@$mobileAppContent->data_values->app_title) }}</p>
            </div>
        </div>
    </div>
</div>
@push('style')
    <style>
        .exchange-currency__thumb {
            max-width: 350px;
            margin: 0 auto;
        }

        .exchange-currency__thumb img {
            width: 100%;
            height: auto;
        }

        .exchange-currency__content {
            max-width: 700px;
            margin: 0 auto;
        }

        @media (max-width:1199px) {
            .exchange-currency__thumb {
                max-width: 350px;
                margin: 0 auto;
            }

            .exchange-currency__content {
                max-width: 500px;
                margin: 0 auto;
            }
        }

        .exchange-currency__content .title {
            margin-bottom: 20px;
        }

        .exchange-currency__content .desc {
            margin-bottom: 20px;
        }

        .exchange-currency__content .text {
            color: hsl(var(--heading));
            margin-top: 20px;
            font-weight: 700;
            font-size: 18px;
        }

        .exchange-currency__wrapper {
            display: flex;
            justify-content: space-around;
            gap: 20px;
        }

        @media (max-width:991px) {
            .exchange-currency__wrapper {
                flex-direction: column;
                gap: 20px;
            }

            .exchange-currency__thumb {
                margin: 0;
            }

            .exchange-currency__content {
                max-width: unset;
                margin: 0;
            }
        }

        .exchange-currency {
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .exchange-currency__shape {
            position: absolute;
            left: 0;
            top: 10px;
            z-index: -1;
            opacity: .2;
        }
    </style>
@endpush
