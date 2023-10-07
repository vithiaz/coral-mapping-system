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
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" /> --}}

{{-- Font Awesome --}}
<link href="{{ asset('/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
<link href="{{ asset('/fontawesome/css/brands.css') }}" rel="stylesheet">
<link href="{{ asset('/fontawesome/css/solid.css') }}" rel="stylesheet">

{{-- GoogleMap api --}}
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1MgLuZuyqR_OGY3ob3M52N46TDBRI_9k&language=id&region=ID"></script> --}}
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

{{-- Deck.gl api --}}
<script src="https://unpkg.com/deck.gl@latest/dist.min.js"></script>


<!-- Scripts -->
{{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<link rel="icon" type="image/x-icon" href="{{ asset('img/logo_small.png') }}">

@stack('stylesheet')

@livewireStyles