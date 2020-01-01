<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="username" content="{{ Auth::user()->name ?? '' }}">
        <meta name="owner" content="{{ $siteSettings['owner'] ?? '' }}">
        <meta name="logo" content="{{ $siteSettings['logo'] ?? '' }}">
        <meta name="title" content="{{ $siteSettings['title'] ?? '' }}">
        <title>{{ $siteSettings["title"] ?? "" }}</title>    
        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        @yield('body')
    </body>
</html>