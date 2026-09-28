@php
    $gatewayElements = getContent('payment_gateway.element');
@endphp

@if ($gatewayElements->count())
    <div class="brand-section py-4">
        <div class="container">
            <div class="brand-slider">
                @foreach ($gatewayElements as $gatewayElement)
                    <div class="single-slide">
                        <div class="brand-item">
                            <img src="{{ frontendImage('payment_gateway', @$gatewayElement->data_values->gateway_image, '100x100') }}"
                                alt="gateway image">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
