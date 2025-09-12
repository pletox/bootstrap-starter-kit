<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>

    @routes

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>


    @vite('resources/js/jquery.js')
    @vite('resources/js/jqueryui.js')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('css')

</head>

<body>
<div class="wrapper">
    @include('layouts.partials._sidebar')

    <div class="main">

        @include('layouts.partials._navbar')

        <main class="content px-3 py-3">
            @yield('content')
        </main>


    </div>

    @if(config('app.enable_bottom_bar'))
        @include('layouts.partials._bottom-bar')
    @endif
</div>
@stack('js')
@livewireScripts
</body>

</html>
