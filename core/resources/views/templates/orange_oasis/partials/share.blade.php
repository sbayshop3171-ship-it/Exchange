<div class="mt-4">
    <h5 class="blog-sidebar__title mt-0 mb-2">@lang('Share')</h5>
    <ul class="list list--row flex-wrap social-list">
        <li>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                class="social-list__icon" target="_blank">
                <i class="fab fa-facebook-f"></i>
            </a>
        </li>
        <li>
            <a href="https://twitter.com/intent/tweet?text={{ __(@$blog->data_values->title) }}%0A{{ url()->current() }}"
                class="social-list__icon" target="_blank">
                <i class="fab fa-twitter"></i>
            </a>
        </li>
        <li>
            <a class="social-list__icon"
                href="http://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&description={{ __(@$blog->data_values->title) }}&media={{ getImage('assets/images/frontend/blog/' . @$blog->data_values->blog_image, '800x580') }}" target="_blank">
                <i class="fab fa-pinterest-p"></i>
            </a>
        </li>
        <li>
            <a class="social-list__icon"
                href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __(@$blog->data_values->title) }}&amp;summary={{ __(@$blog->data_values->short_details) }}" target="_blank">
                <i class="fab fa-linkedin-in"></i>
            </a>
        </li>
    </ul>
</div>
