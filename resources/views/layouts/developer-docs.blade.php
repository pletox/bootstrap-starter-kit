@php
    $currentPage = $page ?? 'index';
    $pageTitle = trim($__env->yieldContent('title')) ?: 'Developer Docs';
    $developerDocsPages = [
        'index' => ['label' => 'Overview', 'icon' => 'lucide-compass'],
        'ui-kit' => ['label' => 'UI Kit', 'icon' => 'lucide-component', 'route' => 'ui-kit'],
        'components' => ['label' => 'Components', 'icon' => 'lucide-panels-top-left'],
        'forms' => ['label' => 'Forms & CRUD', 'icon' => 'lucide-list-checks'],
        'datatables' => ['label' => 'DataTables', 'icon' => 'lucide-table-properties'],
        'infinite-scroll' => ['label' => 'Infinite Scroll', 'icon' => 'lucide-list-end'],
        'backend' => ['label' => 'Backend', 'icon' => 'lucide-server'],
        'testing' => ['label' => 'Testing', 'icon' => 'lucide-flask-conical'],
    ];
@endphp
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <title>{{ $pageTitle }} - Developer Docs - {{ config('app.name') }}</title>
    @include('layouts.partials._pwa')

    @routes

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>

    @vite('resources/js/jquery.js')
    @vite('resources/js/jqueryui.js')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('css')
</head>
<body>
<div class="developer-docs-shell">
    <aside class="developer-docs-sidebar">
        <div class="developer-docs-sidebar-brand">
            <a href="{{ route('developer-docs') }}" class="developer-docs-brand-link">
                <span class="developer-docs-brand-mark">
                    <x-lucide-book-open class="w-5 h-5"/>
                </span>
                <span>
                    <span class="developer-docs-brand-title">Developer Docs</span>
                    <span class="developer-docs-brand-subtitle">{{ config('app.name') }}</span>
                </span>
            </a>
        </div>

        <div class="developer-docs-sidebar-scroll">
            <p class="developer-docs-sidebar-label">Guides</p>

            <nav class="developer-docs-nav">
                @foreach($developerDocsPages as $key => $item)
                    <a
                        href="{{ isset($item['route']) ? route($item['route']) : route('developer-docs', $key === 'index' ? [] : ['page' => $key]) }}"
                        class="developer-docs-nav-link {{ $currentPage === $key ? 'active' : '' }}"
                    >
                        <x-dynamic-component :component="$item['icon']" class="w-4 h-4"/>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="developer-docs-sidebar-footer">
            <a href="{{ route('home') }}" class="developer-docs-footer-link">
                <x-lucide-house class="w-4 h-4"/>
                <span>Dashboard</span>
            </a>
        </div>
    </aside>

    <main class="developer-docs-main">
        <header class="developer-docs-topbar">
            <div>
                <p class="developer-docs-eyebrow mb-1">Local only</p>
                <h1 class="developer-docs-page-title mb-0">{{ $pageTitle }}</h1>
            </div>

            <div class="developer-docs-topbar-actions">
                @hasSection('actions')
                    @yield('actions')
                @else
                    <x-button color="light" link="{{ route('ui-kit') }}">
                        <x-lucide-component class="w-4 h-4"/>
                        <span>UI Kit</span>
                    </x-button>
                    <x-button color="dark" link="{{ route('home') }}">
                        <x-lucide-house class="w-4 h-4"/>
                        <span>Dashboard</span>
                    </x-button>
                @endif
            </div>
        </header>

        <section class="developer-docs-content">
            <div class="developer-docs-article">
                @yield('content')
            </div>
        </section>
    </main>
</div>
@stack('js')
@livewireScripts
</body>
</html>
