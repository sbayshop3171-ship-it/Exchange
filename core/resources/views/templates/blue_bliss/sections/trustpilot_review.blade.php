@php
    $trustpilotReviewContent = getContent('trustpilot_review.content', true);
@endphp
@if ($trustpilotReviewContent)
    <div class="how-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="section-header">
                        <h2 class="title">{{ __(@$trustpilotReviewContent->data_values->heading) }}</h2>
                        <p>{{ __(@$trustpilotReviewContent->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
            <div style="overflow-x: hidden">
                @php echo gs('trustpilot_widget_code'); @endphp
            </div>
        </div>
    </div>

    @push('script')
        <script>
            "use strict";
            (function($) {
                setTimeout(() => {
                    $('body').find(".commonninja-ribbon-link").remove();
                }, 1000);
            })(jQuery);
        </script>
    @endpush

    @push('style')
        <style>
            .iRKbXZ {
                max-width: 100% !important;
            }

            .ejKmWB .review-text p {
                font-family: "Roboto", sans-serif;
            }
        </style>
    @endpush
@endif
