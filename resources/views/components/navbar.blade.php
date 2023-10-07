<nav id="main-navbar">
    <div class="container">
        <div class="content-wrapper">
            <div class="logo-wrapper">
                <div class="logo-container">
                    <img src="{{ asset('img/logo.png') }}"/>                
                </div>
                <div class="logo-title-wrapper">
                    <div class="logo-subtitle">Sistem Pemetaan</div>
                    <div class="logo-title">Terumbu Karang</div>
                </div>
                {{-- <span class="logo-title">Sistem Pemetaan<span class="title-bold">Terumbu Karang</span></span> --}}
            </div>
            <ul class="menu-wrapper">
                <li><a href="{{ route('homepage') }}">Beranda</a></li>
                <li><a href="{{ route('coral-list') }}">Daftar Koordinat</a></li>
                @auth
                    <li class="button-li">
                        <button class="button-default logout ico hovered" onclick="location.href='/logout'">
                            <i class="fa-solid fa-door-open"></i>
                            <span>logout</span>
                        </button>
                    </li>
                @else
                    <li class="button-li">
                        <button class="button-default ico hovered" onclick="showLogin()">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            <span>login</span>
                        </button>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>