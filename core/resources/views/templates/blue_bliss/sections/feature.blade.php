@php
    $featureContent  = getContent('feature.content', true);
    $featureElements = getContent('feature.element');
@endphp

<section class="feature-section padding-top padding-bottom section-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-header">
                    <h2 class="title">{{ __(@$featureContent->data_values->heading) }}</h2>
                    <p> {{ __(@$featureContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-30-none">
            @foreach ($featureElements as $featureElement)
                <div class="col-md-6 col-sm-10 col-xl-4">
                    <div class="feature-item">
                        <div class="feature-thumb">
                            @php echo $featureElement->data_values->feature_icon; @endphp
                        </div>
                        <div class="feature-content">
                            <h5 class="title">{{ __(@$featureElement->data_values->title) }}</h5>
                            <p>{{ __(@$featureElement->data_values->description) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
