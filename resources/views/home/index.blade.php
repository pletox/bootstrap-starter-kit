@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container-fluid px-2">
        <x-page-header
            title="Starter Dashboard"
            subtitle="A practical home base for building authenticated Bootstrap admin panels."
        >
            <x-slot:actions>
                <x-button color="dark" link="{{ route('categories.index') }}" wire:navigate>
                    <x-lucide-layout-grid class="w-4 h-4"/>
                    <span>Manage Categories</span>
                </x-button>

                <x-button color="light" link="{{ route('install-app') }}" wire:navigate>
                    <x-lucide-smartphone class="w-4 h-4"/>
                    <span>Install App</span>
                </x-button>

                @if(app()->isLocal())
                    <x-button color="light" link="{{ route('ui-kit') }}" wire:navigate>
                        <x-lucide-component class="w-4 h-4"/>
                        <span>UI Kit</span>
                    </x-button>
                @endif
            </x-slot:actions>
        </x-page-header>

        @include('home.partials._counters')

        <div class="row g-3">
            <div class="col-12 col-xl-7">
                @include('home.partials._recent_categories')
            </div>

            <div class="col-12 col-xl-5">
                <div class="row g-3">
                    <div class="col-12">
                        @include('home.partials._quick_links')
                    </div>

                    <div class="col-12">
                        <x-card title="Starter Kit Includes" subtitle="Core pieces already wired together.">
                            <div class="d-grid gap-3">
                                <div class="d-flex gap-3">
                                    <x-lucide-shield-check class="w-5 h-5 text-green-700 flex-shrink-0"/>
                                    <div>
                                        <p class="mb-0 fw-medium">Fortify authentication</p>
                                        <p class="mb-0 text-muted text-sm">Login, registration, reset password, verification, and profile settings.</p>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <x-lucide-table-properties class="w-5 h-5 text-blue-700 flex-shrink-0"/>
                                    <div>
                                        <p class="mb-0 fw-medium">Server-side data tables</p>
                                        <p class="mb-0 text-muted text-sm">Reusable DataTables setup with search, sorting, bulk actions, and AJAX flows.</p>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <x-lucide-panels-top-left class="w-5 h-5 text-slate-600 flex-shrink-0"/>
                                    <div>
                                        <p class="mb-0 fw-medium">Blade component system</p>
                                        <p class="mb-0 text-muted text-sm">Buttons, cards, forms, modals, dropdowns, tables, rich text, and inputs.</p>
                                    </div>
                                </div>
                            </div>
                        </x-card>
                    </div>

                    <div class="col-12">
                        <x-card title="Next Build Steps" subtitle="A short path from starter to product.">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 d-flex align-items-center gap-2">
                                    <x-lucide-check class="w-4 h-4 text-green-700"/>
                                    <span class="text-sm">Rename the app and update navigation.</span>
                                </div>
                                <div class="list-group-item px-0 d-flex align-items-center gap-2">
                                    <x-lucide-check class="w-4 h-4 text-green-700"/>
                                    <span class="text-sm">Duplicate the Categories module for your first resource.</span>
                                </div>
                                <div class="list-group-item px-0 d-flex align-items-center gap-2">
                                    <x-lucide-check class="w-4 h-4 text-green-700"/>
                                    <span class="text-sm">Add policies, roles, and product-specific settings.</span>
                                </div>
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
