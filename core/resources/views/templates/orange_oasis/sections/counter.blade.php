@php
    $counterElements = getContent('counter.element');
@endphp
<div class="counter-section pt-60 pb-60">
    <div class="container">
        <div class="row gy-4 align-items-center justify-content-center">
            @foreach (@$counterElements as $counterElement)
                <div class="col-md-3 col-6">
                    <div class="counter-item">
                        <span class="counter-item__icon">
                            @php echo @$counterElement->data_values->icon; @endphp
                        </span>
                        <div class="counter-header">
                            <h4 class="title odometer"
                                data-odometer-final="{{ @$counterElement->data_values->counter_digit }}">0</h4>
                            <h4 class="title">{{ __(@$counterElement->data_values->counter_abbreviation) }}</h4>
                        </div>
                        <div class="counter-content">
                            <h6 class="subtitle">{{ __($counterElement->data_values->title) }}</h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
