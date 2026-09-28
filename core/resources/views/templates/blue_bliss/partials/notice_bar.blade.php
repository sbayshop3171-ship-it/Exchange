@php
    $content = getContent('notice_bar.content', true);
@endphp
@if ($content && gs('show_notice_bar'))
    <div class="top-notice py-2 bg--accent d-flex" id="top-notice">
        <div class="container">
            <div class="row justify-content-center">
                <p class="top-notice-text text-center m-0">
                    {{ __(@$content->data_values->title) }}
                </p>
            </div>
        </div>
        <div class="notice-close px-3 fs--18px">
            <i class="las la-times"></i>
        </div>
    </div>
@endif
