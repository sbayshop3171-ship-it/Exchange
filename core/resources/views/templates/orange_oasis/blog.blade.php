@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="blog-section pt-80 pb-80 bg--light">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="post-item">
                            <div class="post-item__thumb">
                                <a href="{{ route('blog.details', $blog->slug) }}">
                                    <img src="{{ frontendImage('blog', 'thumb_' . $blog->data_values->blog_image, '410x220') }}">
                                </a>
                            </div>
                            <div class="post-item__content">
                                <div class="date mb-2 mb-sm-3 fw-medium">
                                    <span class="icon"><i class="las la-calendar"></i></span>
                                    {{ showDateTime($blog->created_at, 'M d, Y') }}
                                </div>
                                <h4 class="post-item__content-title">
                                    <a href="{{ route('blog.details', $blog->slug) }}">
                                        {{ __(strLimit(@$blog->data_values->title, 50)) }}
                                    </a>
                                </h4>
                                <p>@php echo strLimit(strip_tags(@$blog->data_values->description), 120) @endphp</p>
                                <a href="{{ route('blog.details', $blog->slug) }}" class="mt-3 text--base">
                                    @lang('Read More') <i class="las la-long-arrow-alt-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($blogs->hasPages())
                <div class="row mt-4 ">
                    {{ paginateLinks($blogs) }}
                </div>
            @endif
        </div>
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode(@$sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
