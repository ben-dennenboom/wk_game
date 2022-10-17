<!DOCTYPE html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link href="{{ asset('assets/bootstrap.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/app.css') }}" rel="stylesheet"/>

</head>
<body>
    <div class="container p-4">
        {{ $slot }}
    </div>
    <script src="{{ asset('assets/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap.js') }}"></script>
{{--    <script src="{{ asset('assets/app.js') }}"></script>--}}
    @stack('afterScripts')
</body>
</html>

