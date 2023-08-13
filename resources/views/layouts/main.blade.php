<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>@yield('title') - {{ config('app.name') }} </title>
    @yield('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('files.css')
    @include('files.pwa')
</head>

<body>

    @yield('body')

    @include('files.js')
    @yield('js-code')

</body>

</html>
