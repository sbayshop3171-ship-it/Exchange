@php
    $affiliateContent = getContent('affiliation.content', true);
    $affiliateElements = getContent('affiliation.element',orderById:true);
@endphp

<section class="pt-80 pb-80 affiliation section-bg">
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-md-8">
                <div class="section-title">
                    <div class="section-title__wrapper">
                        <h2 class="section-title__title mb-1">{{ __(@$affiliateContent->data_values->heading) }}</h2>
                        <p class="section-title__desc">{{ __(@$affiliateContent->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4 justify-content-center">
            @forelse ($affiliateElements as $affiliateElement)
                <div class="@if ($loop->odd && $loop->last) col-lg-12 @else col-lg-6 @endif">
                    <div class="affiliate-item">
                        <div class="affiliate-item__left">
                            <h6 class="affiliate-item__subtitle">
                                @lang('LEVEL') {{ @$affiliateElement->data_values->level }}
                            </h6>
                            <h3 class="affiliate-item__title">{{ @$affiliateElement->data_values->commission }}%</h3>
                        </div>
                        <div class="affiliate-item__right">
                            <p class="affiliate-item__desc">
                                {{ __(@$affiliateElement->data_values->description) }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-lg-12 text-center">
                    {{ __($emptyMessage) }}
                </div>
            @endforelse
        </div>
    </div>
</section>
