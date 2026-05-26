<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>@yield('title') - Developer Docs - {{ config('app.name') }}</title>

    @routes

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>

    @vite('resources/js/jquery.js')
    @vite('resources/js/jqueryui.js')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('css')
</head>
<body>
@php
    $currentPage = $page ?? 'index';
    $developerDocsPages = [
        'index' => ['label' => 'Overview', 'icon' => 'lucide-compass'],
        'components' => ['label' => 'Components', 'icon' => 'lucide-panels-top-left'],
        'forms' => ['label' => 'Forms & CRUD', 'icon' => 'lucide-list-checks'],
        'datatables' => ['label' => 'DataTables', 'icon' => 'lucide-table-properties'],
        'infinite-scroll' => ['label' => 'Infinite Scroll', 'icon' => 'lucide-list-end'],
        'backend' => ['label' => 'Backend', 'icon' => 'lucide-server'],
        'testing' => ['label' => 'Testing', 'icon' => 'lucide-flask-conical'],
    ];
@endphp
<main class="bg-body-tertiary min-vh-100">
    <div class="container-fluid px-3 px-lg-4 py-3">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <p class="text-muted text-sm mb-1">Local only</p>
                <h1 class="h3 mb-0">@yield('title')</h1>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <x-button color="light" link="{{ route('ui-kit') }}">
                    <x-lucide-component class="w-4 h-4"/>
                    <span>UI Kit</span>
                </x-button>
                <x-button color="dark" link="{{ route('home') }}">
                    <x-lucide-house class="w-4 h-4"/>
                    <span>Dashboard</span>
                </x-button>
            </div>
        </div>

        <div class="row g-3 align-items-start">
            <aside class="col-12 col-xl-3">
                <x-card class="position-sticky" style="top: 1rem;">
                    <div class="d-grid gap-2">
                        @foreach($developerDocsPages as $key => $item)
                            <a
                                href="{{ route('developer-docs', $key === 'index' ? [] : ['page' => $key]) }}"
                                class="btn {{ $currentPage === $key ? 'btn-dark' : 'btn-light' }} justify-content-start d-flex align-items-center gap-2"
                            >
                                <x-dynamic-component :component="$item['icon']" class="w-4 h-4"/>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </x-card>
            </aside>

            <section class="col-12 col-xl-9">
                @yield('content')
            </section>
        </div>
    </div>
</main>
@stack('js')
@livewireScripts
</body>
</html>
