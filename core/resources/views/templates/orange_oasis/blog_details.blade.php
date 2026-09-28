@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section blog-details pt-80 pb-80">
        <div class="container">
            <div class="row gy-4 gy-md-5">
                <div class="col-lg-8 col-md-12 pe-lg-4 pe-xl-5">
                    <div class="post-item p-0">
                        <div class="post-item__thumb">
                            <img src="{{ frontendImage('blog', @$blog->data_values->blog_image, '820x440') }}">
                        </div>
                        <div class="post-item__content mt-3 mt-sm-4 p-0">
                            <ul class="d-flex flex-wrap gap-4 mb-3 align-items-center">
                                <li><i class="far fa-calendar"></i> {{ @$blog->created_at->format('d M Y') }}</li>
                            </ul>
                            <h3 class="post-item__content-title">{{ __(@$blog->data_values->title) }}</h3>
                            <div class="mb-3">
                                @php echo $blog->data_values->description; @endphp
                            </div>
                        </div>
                    </div>

                    @include($activeTemplate . 'partials.share')

                    <div class="fb-comments" data-href="{{ route('blog.details', @$blog->slug) }}" data-numposts="5"></div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-sidebar">
                        <h4 class="blog-sidebar__title">@lang('Latest Blogs')</h4>
                        <ul class="latest-posts m-0">
                            @foreach ($blogs as $blog)
                                <li>
                                    <div class="post-thumb">
                                        <a href="{{ route('blog.details', $blog->slug) }}">
                                            <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->blog_image, '410x220') }}">
                                        </a>
                                    </div>
                                    <div class="post-info">
                                        <h6 class="title">
                                            <a href="{{ route('blog.details', @$blog->slug) }}">
                                                {{ strLimit(__(@$blog->data_values->title), 55) }}
                                            </a>
                                        </h6>
                                        <span class="posts-date"><i class="far fa-calendar-alt"></i>
                                            {{ @$blog->created_at->format('d M Y') }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
