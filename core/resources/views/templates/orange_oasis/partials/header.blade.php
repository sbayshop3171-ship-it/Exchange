<div class="header">
    <div class="container">
        <div class="header-bottom">
            <div class="header-bottom-area align-items-center">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo() }}" alt="logo">
                    </a>
                </div>
                <ul class="menu">
                    <li>
                        <a class="{{ menuActive('home') }}" href="{{ route('home') }}">@lang('Home')</a>
                    </li>
                    @foreach ($pages as $page)
                        <li>
                            <a class="{{ menuActive('pages', param: $page->slug) }}"
                               href="{{ route('pages', $page->slug) }}">
                                {{ __($page->name) }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('faq') }}" class="{{ menuActive('faq') }}">@lang('Faq')</a>
                    </li>
                    <li>
                        <a href="{{ route('blog') }}" class="{{ menuActive('blog*') }}">@lang('Blog')</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="{{ menuActive('contact') }}">@lang('Contact')</a>
                    </li>
                    <li class="d-lg-none d-block">
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            @auth
                                <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">
                                    <i class="la la-dashboard"></i> @lang('Dashboard')
                                </a>
                                <a href="{{ route('user.logout') }}" class="btn btn--outline-base btn-sm">
                                    <i class="la la-sign-out"></i> @lang('Logout')
                                </a>
                            @else
                                <a href="{{ route('user.login') }}" class="btn btn--base btn-sm">@lang('Login')</a>
                            @endauth
                        </div>
                    </li>
                </ul>
                <div class=" d-lg-flex gap-3 ms-lg-0 ms-auto">
                    <div class="d-none d-lg-flex gap-3">
                        @auth
                            <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">
                                <i class="la la-dashboard"></i> @lang('Dashboard')
                            </a>
                            <a href="{{ route('user.logout') }}" class="btn btn--outline-base btn-sm">
                                <i class="la la-sign-out"></i> @lang('Logout')
                            </a>
                        @else
                            <a href="{{ route('user.login') }}" class="btn btn--base btn-sm">@lang('Login')</a>
                        @endauth
                    </div>

                    @include($activeTemplate . 'partials.language')

                </div>
                <div class="header-trigger-wrapper d-flex d-lg-none align-items-center">
                    <div class="header-trigger">
                        <i class="las la-bars"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
