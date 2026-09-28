<header>
    @include($activeTemplate . 'partials.top_header')
    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-area">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo('dark') }}" alt="{{ gs('site_name') }}">
                    </a>
                </div>
                <div class="menu-area">
                    <ul class="menu">
                        <li class="menu-item">
                            <a href="{{ route('home') }}" class="menu-item__link {{ menuActive('home') }}">
                                @lang('Home')
                            </a>
                        </li>
                        @foreach ($pages as $page)
                            <li class="menu-item">
                                <a href="{{ route('pages', $page->slug) }}"
                                    class="menu-item__link {{ menuActive('pages', param: $page->slug) }}">
                                    {{ __($page->name) }}
                                </a>
                            </li>
                        @endforeach
                        <li class="menu-item">
                            <a href="{{ route('blog') }}" class="menu-item__link {{ menuActive('blog*') }}">
                                @lang('Blog')
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('contact') }}" class="menu-item__link {{ menuActive('contact') }}">
                                @lang('Contact')
                            </a>
                        </li>
                        @guest
                            <li class="d-lg-none d-block">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{ route('user.login') }}" class="btn btn--base-outline btn-sm">
                                        @lang('Login')
                                    </a>
                                    <a href="{{ route('user.register') }}" class="btn btn--base btn-sm">
                                        @lang('Register')
                                    </a>
                                </div>
                            </li>
                        @else
                            <li class="d-lg-none d-block">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">
                                        @lang('Dashboard')
                                    </a>
                                    <a href="{{ route('user.logout') }}" class="btn btn--base-outline btn-sm">
                                        @lang('Logout')
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                    @guest
                        <div class="header-buttons d-lg-block d-none">
                            <a href="{{ route('user.login') }}" class="btn btn--base-outline btn-sm">
                                @lang('Login')
                            </a>
                            <a href="{{ route('user.register') }}" class="btn btn--base btn-sm ">
                                @lang('Register')
                            </a>
                        </div>
                    @else
                        <div class="header-buttons d-lg-block d-none">
                            <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">
                                @lang('Dashboard')
                            </a>
                            <a href="{{ route('user.logout') }}" class="btn btn--base-outline btn-sm">
                                @lang('Logout')
                            </a>
                        </div>
                    @endguest

                    @include($activeTemplate . 'partials.language')

                    <div class="header-bar-area d-lg-none">
                        <div class="header-bar">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
