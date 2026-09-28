@php
    $affiliationContent = getContent('affiliation.content', true);
    $affiliationElements = getContent('affiliation.element', false, null, true);
@endphp
@if (@$affiliationContent)
    <section class="affiliate-section padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-8">
                    <div class="section-header">
                        <h2 class="title">{{ __(@$affiliationContent->data_values->heading) }}</h2>
                        <p>{{ __(@$affiliationContent->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center gy-4">
                @foreach (@$affiliationElements as $affiliationElement)
                    <div class="@if ($loop->odd && $loop->last) col-lg-12 @else col-lg-6 @endif">
                        <div class="affiliate-item h-100">
                            <div class="affiliate-thumb">
                                <span class="cate">@lang('LEVEL') {{ @$affiliationElement->data_values->level }}</span>
                                <h6 class="title text-white">{{ @$affiliationElement->data_values->commission }}%</h6>
                            </div>
                            <div class="affiliate-content d-flex align-items-center flex-wrap">
                                {{ __(@$affiliationElement->data_values->description) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
