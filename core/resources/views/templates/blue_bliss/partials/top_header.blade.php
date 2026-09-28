@php
    $topHeaderContactContent = getContent('contact_us.content', true);
@endphp
<div class="header-top">
    <div class="container">
        <div class="header-top-area">
            <div class="header-wrapper">
                <div class="header-top-item">
                    <div class="header-top-icon">
                        @php echo @$topHeaderContactContent->data_values->address_icon; @endphp
                    </div>
                    <div class="header-top-content">
                        <span>{{ __(@$topHeaderContactContent->data_values->address) }}</span>
                    </div>
                </div>
                <div class="header-top-item">
                    <div class="header-top-icon">
                        @php echo @$topHeaderContactContent->data_values->email_icon; @endphp
                    </div>
                    <div class="header-top-content">
                        <a href="mailto:{{ @$topHeaderContactContent->data_values->email }}">
                            {{ @$topHeaderContactContent->data_values->email }}
                        </a>
                    </div>
                </div>
                <div class="header-top-right-item header-top-item">
                    <a href="tel:{{ @$topHeaderContactContent->data_values->mobile }}">
                        @php echo @$topHeaderContactContent->data_values->mobile_icon; @endphp
                    </a>
                    <span>{{ @$topHeaderContactContent->data_values->mobile }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
