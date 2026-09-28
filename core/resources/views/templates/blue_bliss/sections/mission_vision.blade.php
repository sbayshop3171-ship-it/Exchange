@php
    $missionVisionElements = getContent('mission_vision.element');
@endphp
@if ($missionVisionElements)
    <section class="overview-section ">
        @foreach ($missionVisionElements as $missionVisionElement)
            <div class="overview-item section-bg">
                <div class="container mw-lg-100 p-0">
                    <div class="row m-0 {{ $loop->even ? 'flex-row-reverse' : '' }}">
                        <div class="col-lg-6 p-lg-0">
                            <div class="overview-contnent padding-top padding-bottom">
                                <div class="content">
                                    <div class="section-header left-style margin-olpo text-left">
                                        <h2 class="title">{{ __(@$missionVisionElement->data_values->heading) }}</h2>
                                        <p> {{ __(@$missionVisionElement->data_values->subheading) }}</p>
                                    </div>
                                    @php echo $missionVisionElement->data_values->description; @endphp
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 p-lg-0 bg_img"
                            data-background="{{ frontendImage('mission_vision', $missionVisionElement->data_values->image, '1000x675') }}">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endif
