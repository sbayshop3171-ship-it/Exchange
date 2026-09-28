<header>
    @include($activeTemplate . 'partials.top_header')

    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-area">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo('dark') }}">
                    </a>
                </div>
                <div class="menu-area">
                    <ul class="menu">
                        <li class="menu-item">
                            <a class="menu-item__link" href="{{ route('home') }}">@lang('Home')</a>
                        </li>
                        <li class="menu-item">
                            <a class="menu-item__link {{ menuActive('user.exchange.*') }}" href="#">
                                @lang('Exchanges')
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('user.exchange.list', 'list') }}" class="{{ menuActive('user.exchange.list', null, 'list') }}">
                                        @lang('All Exchange')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.exchange.list', 'initiated') }}" class="{{ menuActive('user.exchange.list', null, 'initiated') }}">
                                        @lang('Initiated Exchange')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.exchange.list', 'approved') }}" class="{{ menuActive('user.exchange.list', null, 'approved') }}">
                                        @lang('Approved Exchange')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.exchange.list', 'pending') }}" class="{{ menuActive('user.exchange.list', null, 'pending') }}">
                                        @lang('Pending Exchange')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.exchange.list', 'refunded') }}" class="{{ menuActive('user.exchange.list', null, 'refunded') }}">
                                        @lang('Refunded Exchange')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.exchange.list', 'canceled') }}" class="{{ menuActive('user.exchange.list', null, 'canceled') }}">
                                        @lang('Canceled Exchange')
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-item__link {{ menuActive('ticket.*') }}">
                                @lang('Ticket')
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('ticket.open') }}" class="{{ menuActive('ticket.open') }}">
                                        @lang('New Ticket')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('ticket.index') }}" class="{{ menuActive('ticket.index') }}">
                                        @lang('My Ticket')
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-item__link {{ menuActive('user.withdraw.*') }}">
                                @lang('Withdraw')
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('user.withdraw.index') }}" class="{{ menuActive('user.withdraw.index') }}">
                                        @lang('Withdraw Money')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.withdraw.history') }}" class="{{ menuActive('user.withdraw.history') }}">
                                        @lang('Withdraw Log')
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="menu-item__link {{ menuActive(['user.affiliate.index', 'user.report.commission.log', 'user.profile.setting', 'user.twofactor', 'user.change.password']) }}">
                                @lang('Account')
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('user.affiliate.index') }}" class="{{ menuActive('user.affiliate.index') }}">
                                        @lang('Affiliation')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.report.commission.log') }}" class="{{ menuActive('user.report.commission.log') }}">
                                        @lang('Commission Logs')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.transactions') }}" class="{{ menuActive('user.report.transactions') }}">
                                        @lang('Transactions')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.profile.setting') }}" class="{{ menuActive('user.profile.setting') }}">
                                        @lang('Profile Setting')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.twofactor') }}" class="{{ menuActive('user.twofactor') }}">
                                        @lang('2FA Security')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.change.password') }}" class="{{ menuActive('user.change.password') }}">
                                        @lang('Change Password')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.logout') }}">
                                        @lang('Logout')
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="d-lg-none d-block">
                            <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">
                                @lang('Dashboard')
                            </a>
                        </li>
                    </ul>
                    <div class="d-lg-block d-none">
                        <a href="{{ route('user.home') }}" class="btn btn--base btn-sm">
                            @lang('Dashboard')
                        </a>
                    </div>

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
