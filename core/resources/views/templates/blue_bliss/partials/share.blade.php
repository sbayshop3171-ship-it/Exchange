<h5 class="mb-2 mt-5">@lang('Like this post? Share it your social network')</h5>
<ul class="list list--row social-list justify-content-start">
    <li>
        <a target="_blank" class="t-link social-list__icon" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}">
            <i class="lab la-facebook-f"></i>
        </a>
    </li>
    <li>
        <a target="_blank" class="t-link social-list__icon "
            href="https://x.com/intent/tweet?text={{ __(@$blog->data_values->title) }}%0A{{ url()->current() }}">
            <i class="fa-brands fa-x-twitter"></i>
        </a>
    </li>
    <li>
        <a target="_blank" class="t-link social-list__icon"
            href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __(@$blog->data_values->title) }}&amp;summary={{ __(@$blog->data_values->description) }}">
            <i class="lab la-linkedin-in"></i>
        </a>
    </li>
    <li>
        <a target="_blank" class="t-link social-list__icon"
            href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __(@$blog->data_values->title) }}&media={{ getImage('assets/images/frontend/blog/' . $blog->data_values->blog_image, '840x480') }}">
            <i class="lab la-pinterest"></i>
        </a>
    </li>
</ul>
