@php
    $howItWorksContent = getContent('how_it_work.content', true);
    $howItWorksElements = getContent('how_it_work.element', orderById: true);
@endphp
@if ($howItWorksContent)
    <section class="how-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="section-header">
                        <h2 class="title">{{ __(@$howItWorksContent->data_values->heading) }}</h2>
                        <p>{{ __(@$howItWorksContent->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center gy-4">
                @foreach ($howItWorksElements as $howItWorksElement)
                    <div class="col-xl-4 col-sm-8 col-lg-7">
                        <div class="how-item">
                            <div class="how-thumb"> @php echo @$howItWorksElement->data_values->icon; @endphp </div>
                            <div class="how-content text-center">
                                <h5 class="title">{{ __(@$howItWorksElement->data_values->title) }}</h5>
                                <p class="desc">{{ __(@$howItWorksElement->data_values->subtitle) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
