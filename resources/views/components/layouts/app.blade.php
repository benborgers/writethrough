<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>writethrough</title>

        @vite('resources/css/app.css')
    </head>
    <body class="antialiased text-gray-700 bg-white">
        {{ $slot }}
    </body>
</html>
