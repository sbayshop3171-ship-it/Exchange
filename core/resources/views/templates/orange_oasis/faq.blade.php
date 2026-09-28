@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pt-80 pb-80">
        @if ($faqs->count())
            <div class="container">
                <div class="row gy-4">
                    @foreach ($faqs as $element)
                        <div class="col-lg-6 pe-lg-4">
                            <div class="d-flex gap-3 faq-item-wrapper">
                                <div class="faq-icon"><i class="fas fa-question"></i></div>
                                <div class="faq-item">
                                    <div class="faq-item__title">
                                        <h6 class="title">{{ __(@$element->data_values->question) }}</h6>
                                    </div>
                                    <div class="faq-item__content">
                                        <div class="py-3">{{ __(@$element->data_values->answer) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode(@$sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
