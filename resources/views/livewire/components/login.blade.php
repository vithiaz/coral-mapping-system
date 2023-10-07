<div wire:ignore.self id="login-sidenav" class="">
    <div class="overlay"></div>
    <div class="background">
        <div class="body">
            <div class="login-head-wrapper">
                <div class="logo-wrapper">
                    <div class="logo-container">
                        <img src="{{ asset('img/logo.png') }}"/>                
                    </div>
                </div>
                <button id="login-modal-close" class="button-default"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="login-form-wrapper">
                <span class="form-title">LOGIN</span>
                <form class="login-form input-form column-wrap" wire:submit.prevent='login'>
                    <div class="form-floating mb-3">
                        <input wire:model='username' type="text" class="form-control default-input @error('username') is-invalid @enderror" placeholder="Username" id="login-username">
                        <label for="login-username">Username</label>
                        {{-- <small class="error">Lorem ipsum dolor sit amet.</small> --}}
                    </div>
                    <div class="form-floating mb-3">
                        <input wire:model='password' type="password" class="form-control default-input @error('password') is-invalid @enderror" placeholder="Password" id="login-password">
                        <label for="login-password">Password</label>
                    </div>
                    @if (session('auth-invalid'))
                        <div class="error-wrapper">
                            <small class="error">{{ session('auth-invalid') }}</small>
                        </div>
                    @endif
                    <div class="button-wrapper">
                        <button type="submit" class="btn button-default login-button">LOGIN</button>
                        {{-- <div class="register-wrapper">
                            <span>belum punya akun ?</span>
                            <span><a href="#">Buat akun</a></span>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('script')
    <script>
        let loginSideNav = $('#login-sidenav')

        function showLogin() {
            $('#login-sidenav').css('display', 'flex')
            
            setTimeout(function() {
                loginSideNav.addClass('show')
            }, 150);            
        }

        function hideLogin() {
            loginSideNav.removeClass('show')
            
            setTimeout(function() {
                $('#login-sidenav').css('display', 'none')
            }, 500);
        }
    
        $('#login-modal-close').click(function () {
            hideLogin()
        })

        loginSideNav.find('.overlay').first().click(function () {
            hideLogin()
        })
        
    
    </script>
    @endpush
</div>

