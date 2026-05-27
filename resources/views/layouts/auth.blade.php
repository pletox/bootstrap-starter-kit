<!DOCTYPE html>
<html lang="en" data-bs-theme="light" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    @include('layouts.partials._pwa')

    @vite('resources/js/jquery.js')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="h-100 auth-bg">

<div class="auth-shell d-flex min-h-full flex-column justify-content-center">
    <div class="mx-auto auth-container @yield('auth-container-class') px-4">
        <div class="d-flex align-items-center justify-content-center mb-3">
            <a href="/">
               <x-application-logo />
            </a>
        </div>

        @yield('content')
    </div>
</div>
@stack('js')
@livewireScripts

</body>
</html>
