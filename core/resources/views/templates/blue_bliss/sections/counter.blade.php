@php
    $counterContent = getContent('counter.content', true);
    $counterElements = getContent('counter.element');
@endphp
<div class="counter-section padding-top padding-bottom bg-overlay bg_fixed bg_img"
    data-background="{{ frontendImage('counter', @$counterContent->data_values->image, '1200x335') }}">
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            @foreach (@$counterElements as $counterElement)
                <div class="col-lg-3 col-sm-6">
                    <div class="counter-item">
                        <div class="counter-header">
                            <h4 class="title odometer" data-odometer-final="{{ $counterElement->data_values->counter_digit }}">0</h4>
                            <h4 class="title">{{ __($counterElement->data_values->counter_abbreviation) }}</h4>
                        </div>
                        <div class="counter-content">
                            <h6 class="subtitle">{{ __($counterElement->data_values->title) }}</h6>
                        </div>
                        <div class="icon">
                            @php echo $counterElement->data_values->counter_icon; @endphp
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
