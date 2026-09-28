@php
    $blogContent = getContent('blog.content', true);
    $blogElements = getContent('blog.element', false, 3);
@endphp
<section class="blog-section padding-bottom padding-top">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-header">
                    <h2 class="title">{{ __(@$blogContent->data_values->heading) }}</h2>
                    <p> {{ __(@$blogContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-3">
            @foreach ($blogElements as $blogElement)
                <div class="col-md-6 col-xl-4 col-sm-10">
                    <div class="post-item">
                        <div class="post-thumb c-thumb">
                            <a href="{{ route('blog.details', $blogElement->slug) }}">
                                <img class="w-100" src="{{ frontendImage('blog', 'thumb_' . @$blogElement->data_values->blog_image, '410x220') }}"
                                    alt="blog image">
                            </a>
                        </div>
                        <div class="post-content">
                            <div class="meta-post">
                                <div class="date blog-date">
                                    <span class="d-inline-block">
                                        <i class="far fa-calendar-alt text-muted"></i>
                                        {{ showDateTime($blogElement->created_at, 'M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="blog-header pt-0">
                                <h6 class="title">
                                    <a href="{{ route('blog.details', $blogElement->slug) }}">
                                        {{ __($blogElement->data_values->title) }}
                                    </a>
                                </h6>
                            </div>

                            <div class="entry-content">
                                @php echo strLimit(strip_tags(@$blogElement->data_values->description), 120) @endphp
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
