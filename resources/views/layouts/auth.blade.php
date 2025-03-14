    <!DOCTYPE html>
<html lang="en" data-bs-theme="light" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>

    @vite('resources/js/jquery.js')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="h-100 auth-bg">

<div class="d-flex min-h-full flex-column justify-content-center">
    <div class="mt-10 mx-auto auth-container px-4">
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
