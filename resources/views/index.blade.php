<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Template</title>
        <meta content="" name="descriptison">
        <meta content="" name="keywords">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <style>
            #toast-container {
                font-family: 'Roboto Condensed', 'Courier New', Courier, monospace;
                opacity: 1;
            }

            #toast-container > .toast-success {
                background-image: url("data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20style%3D%22fill%3A%20rgba(0%2C%200%2C%200%2C%201)%3Btransform%3A%20%3BmsFilter%3A%3B%22%3E%3Cpath%20d%3D%22m10%2015.586-3.293-3.293-1.414%201.414L10%2018.414l9.707-9.707-1.414-1.414z%22%3E%3C%2Fpath%3E%3C%2Fsvg%3E") !important;
                color: #000;
                background-color: #ffc117;
                opacity: 1;
            }

            #toast-container > .toast-error {
                background-image: url("data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20style%3D%22fill%3A%20rgba(255%2C%20255%2C%20255%2C%201)%3Btransform%3A%20%3BmsFilter%3A%3B%22%3E%3Cpath%20d%3D%22M11.953%202C6.465%202%202%206.486%202%2012s4.486%2010%2010%2010%2010-4.486%2010-10S17.493%202%2011.953%202zM12%2020c-4.411%200-8-3.589-8-8s3.567-8%207.953-8C16.391%204%2020%207.589%2020%2012s-3.589%208-8%208z%22%3E%3C%2Fpath%3E%3Cpath%20d%3D%22M11%207h2v7h-2zm0%208h2v2h-2z%22%3E%3C%2Fpath%3E%3C%2Fsvg%3E") !important;
                color: #FFF;
                background-color: #ff1717;
                opacity: 1;
            }

            #toast-container .toast-message{
                font-size: .8rem;
                font-style: italic;
            }

        </style>
    </head>
    <body>
        <div id="app">
            <app></app>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        {{-- <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'pusherKey' => config('broadcasting.connections.pusher.key'),
            ]) !!};
        </script> --}}
    </body>
</html>
