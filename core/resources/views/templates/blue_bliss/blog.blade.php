@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center gy-3">
                @foreach ($blogs as $blog)
                    <div class="col-md-6 col-xl-4 col-sm-10">
                        <div class="post-item">
                            <div class="post-thumb c-thumb">
                                <a href="{{ route('blog.details', $blog->slug) }}">
                                    <img class="w-100"
                                        src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->blog_image, '410x220') }}">
                                </a>
                            </div>
                            <div class="post-content">
                                <div class="meta-post">
                                    <div class="date blog-date">
                                        <span class="d-inline-block">
                                            <i class="far fa-calendar-alt text-muted"></i>
                                            {{ showDateTime($blog->created_at, 'M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="blog-header pt-0">
                                    <h6 class="title">
                                        <a href="{{ route('blog.details', $blog->slug) }}">
                                            {{ __($blog->data_values->title) }}
                                        </a>
                                    </h6>
                                </div>

                                <div class="entry-content">
                                    @php echo strLimit(strip_tags(@$blog->data_values->description), 120) @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($blogs->hasPages())
                <div class="mt-4">
                    {{ paginateLinks($blogs) }}
                </div>
            @endif
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode(@$sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
