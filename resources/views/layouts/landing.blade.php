<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#7d0b18">
    <title>@yield('title', 'KumbhSnaan.com')</title>
    <meta name="description" content="Experience the sacred Nashik Simhastha Kumbh from anywhere with a verified digital snaan ritual.">
    @vite(['resources/sass/app.scss', 'resources/js/jquery.js', 'resources/js/landing.js'])
</head>
<body class="landing-page">
    @yield('content')
</body>
</html>
