<nav class="light-blue lighten-1">
    <div class="nav-wrapper container">
        <a href="/" class="brand-logo">Logo</a>
        <a href="#" data-target="mobile-sidenav" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
            @auth
                <li class="{{ Request::url() === route('chat.index') ? 'active' : '' }}">
                    <a href="{{ route('chat.index') }}">Chat</a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" 
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @else
                <li class="{{ Request::url() === route('login') ? 'active' : '' }}">
                    <a href="{{ route('login') }}">Login</a>
                </li>
                <li class="{{ Request::url() === route('register') ? 'active' : '' }}">
                    <a href="{{ route('register') }}">Register</a>
                </li>
            @endauth
        </ul>
    </div>
</nav>

<ul class="sidenav" id="mobile-sidenav">
    @auth
        <li class="{{ Request::url() === route('chat.index') ? 'active' : '' }}">
            <a href="{{ route('chat.index') }}">Chat</a>
        </li>
    @else
        <li class="{{ Request::url() === route('login') ? 'active' : '' }}">
            <a href="{{ route('login') }}">Login</a>
        </li>
        <li class="{{ Request::url() === route('register') ? 'active' : '' }}">
            <a href="{{ route('register') }}">Register</a>
        </li>
    @endauth
</ul>