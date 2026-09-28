@php
    $blogContent = getContent('blog.content', true);
    $blogElements = getContent('blog.element')->take(3);
@endphp
<div class="blog-section pt-80 pb-80 bg--light">
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-md-12">
                <div class="section-title">
                    <div class="section-title__wrapper">
                        <h2 class="section-title__title mb-1">{{ __(@$blogContent->data_values->heading) }}</h2>
                        <p class="section-title__desc">{{ __(@$blogContent->data_values->subheading) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4 justify-content-center">
            @foreach ($blogElements as $blogElement)
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="post-item">
                        <div class="post-item__thumb">
                            <a href="{{ route('blog.details', $blogElement->slug) }}">
                                <img
                                    src="{{ frontendImage('blog', 'thumb_' . $blogElement->data_values->blog_image, '410x220') }}">
                            </a>
                        </div>
                        <div class="post-item__content">
                            <div class="date mb-2 mb-sm-3 fw-medium">
                                <span class="icon"><i class="las la-calendar"></i></span>
                                {{ showDateTime($blogElement->created_at, 'M d, Y') }}
                            </div>
                            <h4 class="post-item__content-title">
                                <a href="{{ route('blog.details', $blogElement->slug) }}">
                                    {{ __(strLimit(@$blogElement->data_values->title, 50)) }}
                                </a>
                            </h4>
                            <p>@php echo strLimit(strip_tags(@$blogElement->data_values->description), 120) @endphp</p>
                            <a href="{{ route('blog.details', $blogElement->slug) }}" class="mt-3 text--base">
                                @lang('Read More')<i class="las la-long-arrow-alt-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
