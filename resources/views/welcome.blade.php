
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Cities-app') }}</title>
        @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="antialiased">
        @include('components.header')
        <main>
                @yield('main')
        </main>
        @include('components.footer')
        @yield('script')
    </body>
</html>
