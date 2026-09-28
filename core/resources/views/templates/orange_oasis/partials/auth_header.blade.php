<div class="header">
    <div class="container">
        <div class="header-bottom">
            <div class="header-bottom-area align-items-center">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo() }}" alt="logo">
                    </a>
                </div>
                <ul class="menu ms-auto">
                    <li class="menu-item">
                        <a href="{{ route('home') }}" class="menu-item__link {{ menuActive('home') }}">@lang('Home')</a>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0)"
                           class="{{ menuActive(['user.exchange.list', 'user.exchange.details']) }} menu-item__link">@lang('Exchange History')</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('user.exchange.list', 'list') }}" class="{{ menuActive('user.exchange.list', null, 'list') }}">
                                    @lang('All Exchange')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.exchange.list', 'initiated') }}"
                                   class="{{ menuActive('user.exchange.list', null, 'initiated') }}">
                                    @lang('Initiated Exchange')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.exchange.list', 'approved') }}"
                                   class="{{ menuActive('user.exchange.list', null, 'approved') }}">
                                    @lang('Approved Exchange')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.exchange.list', 'pending') }}"
                                   class="{{ menuActive('user.exchange.list', null, 'pending') }}">
                                    @lang('Pending Exchange')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.exchange.list', 'refunded') }}"
                                   class="{{ menuActive('user.exchange.list', null, 'refunded') }}">
                                    @lang('Refunded Exchange')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.exchange.list', 'canceled') }}"
                                   class="{{ menuActive('user.exchange.list', null, 'canceled') }}">
                                    @lang('Canceled Exchange')
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0)" class="{{ menuActive('user.withdraw.*') }} menu-item__link">
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
                        <a href="javascript:void(0)" class="{{ menuActive('ticket.*') }} menu-item__link">
                            @lang('Ticket')
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('ticket.open') }}" class="{{ menuActive('ticket.open') }}">
                                    @lang('Open New Ticket')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ticket.index') }}" class="{{ menuActive('ticket.index') }}">
                                    @lang('My Tickets')
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0)"
                           class="{{ menuActive(['user.affiliate.index', 'user.change.password', 'user.profile.setting', 'user.twofactor', 'user.report.commission.log']) }} menu-item__link">
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
                    <li>
                        <a href="{{ route('user.home') }}" class="btn btn--base btn-sm mt-lg-0 mt-3 d-inline-block">
                            @lang('Dashboard')
                        </a>
                    </li>
                </ul>
                <div class="custom-dropdown-wrapper">
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
