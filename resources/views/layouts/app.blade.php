<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @include('layouts.dependencies.head')
    
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
    
    <main>

        @include('components.navbar')

        {{ $slot }}


        <livewire:components.login />
    </main>


    {{-- Session Message --}}
    <div id="session-message" class="session-message-card" data-notify='{{ session('message') }}'>
        <div class="content">
            <i class="success fa-solid fa-circle-check"></i>
            <i class="info fa-solid fa-circle-info"></i>
            <i class="danger fa-solid fa-circle-exclamation"></i>
            <span class="message">Message Placeholder</span>
        </div>
        <div class="close-button">
            <i class="success fa-solid fa-xmark"></i>
        </div>
    </div>


    @include('layouts.dependencies.tail')
    
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
            let details = event.detail.message
            console.log(details)

            $('.session-message-card').removeClass('success');
            $('.session-message-card').removeClass('danger');
            $('.session-message-card').removeClass('info');

            // details[0] => [success, danger, info]
            show_message(details[1], details[0]);            
        });
    </script>

    {{-- Helper JS Script --}}
    <script>
        $( window ).on('refresh-page', function() {
            window.location.reload(false);
        })
    </script>

</body>
</html>