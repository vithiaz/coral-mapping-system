<!DOCTYPE html>
<html lang="en" wire:live>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    {{-- SwiperJS CDN --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    {{-- Font Awesome --}}
    <link href="{{ asset('/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('/fontawesome/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('/fontawesome/css/solid.css') }}" rel="stylesheet">

    {{-- GoogleMap api --}}
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1MgLuZuyqR_OGY3ob3M52N46TDBRI_9k&language=id&region=ID"></script> --}}
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>


    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('stylesheet')
    
    @livewireStyles
    
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
    
    {{-- Content Here --}}
    @yield('base_layout_content')


    {{-- Session Message --}}
    {{-- <div id="session-message" class="session-message-card" data-notify='{{ session('message') }}'>
        <div class="content">
            <i class="success fa-solid fa-circle-check"></i>
            <i class="info fa-solid fa-circle-info"></i>
            <i class="danger fa-solid fa-circle-exclamation"></i>
            <span class="message">Message Placeholder</span>
        </div>
        <div class="close-button">
            <i class="success fa-solid fa-xmark"></i>
        </div>
    </div> --}}


    {{-- Dependencies --}}

    {{-- JQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    {{-- Bootstrap 5 Scripts CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    {{-- SwiperJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script> --}}
    
    {{-- PowerGrid Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" 
            crossorigin="anonymous"></script>
    
    {{-- AlpineJS  --}}
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}


    {{-- Google Map Api --}}
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1MgLuZuyqR_OGY3ob3M52N46TDBRI_9k&callback=initMap&v=weekly"
      defer
    ></script>


    {{-- Session Message Events Scripts--}}
    <script>
        function show_message($msg, $class) {
            $('.session-message-card').addClass($class);
            $('.session-message-card .message').text($msg);
            $('.session-message-card').addClass('active');
            setTimeout(function() {
                $('.session-message-card').removeClass('active');
            }, 3000);
        }

        $(document).ready(function () {
            $('.session-message-card').removeClass('success');
            $('.session-message-card').removeClass('danger');
            $('.session-message-card').removeClass('info');

            if( $('#session-message').data('notify') ) {
                show_message($('#session-message').data('notify'), 'success');
            }
        });

        $('.session-message-card .close-button').click(function () {
            $('.session-message-card').removeClass('active');
        });

        $(window).on('display-message', function (event) {
            let details = event.detail[0]

            $('.session-message-card').removeClass('success');
            $('.session-message-card').removeClass('danger');
            $('.session-message-card').removeClass('info');

            if (details.success) {
                show_message(details.success, 'success');
            }
            else if (details.error) {
                show_message(details.error, 'danger');          
            }
            else {
                show_message(details.info, 'info');
            }
        });

    </script>



    @livewireScripts
    @stack('script')
    
    <script src="{{ asset('js/app.js') }}"></script>

    


</body>
</html>