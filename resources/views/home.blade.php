@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container-fluid px-2">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <x-heading>Starter Dashboard</x-heading>
                <x-text class="mb-0">A practical home base for building authenticated Bootstrap admin panels.</x-text>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <x-button color="dark" link="{{ route('categories.index') }}">
                    <x-lucide-layout-grid class="w-4 h-4"/>
                    <span>Manage Categories</span>
                </x-button>

                <x-button color="light" link="{{ route('ui-kit') }}">
                    <x-lucide-component class="w-4 h-4"/>
                    <span>UI Kit</span>
                </x-button>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-md-4">
                <x-card class="h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-sm mb-1">Categories</p>
                            <h3 class="mb-0">{{ number_format($categoryStats['total']) }}</h3>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded bg-gray-100 w-10 h-10">
                            <x-lucide-database class="w-5 h-5 text-slate-600"/>
                        </div>
                    </div>
                </x-card>
            </div>

            <div class="col-12 col-md-4">
                <x-card class="h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-sm mb-1">Active Records</p>
                            <h3 class="mb-0">{{ number_format($categoryStats['active']) }}</h3>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded bg-green-100 w-10 h-10">
                            <x-lucide-circle-check class="w-5 h-5 text-green-700"/>
                        </div>
                    </div>
                </x-card>
            </div>

            <div class="col-12 col-md-4">
                <x-card class="h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-sm mb-1">Inactive Records</p>
                            <h3 class="mb-0">{{ number_format($categoryStats['inactive']) }}</h3>
                        </div>
                        <div class="d-flex align-items-center justify-content-center rounded bg-yellow-100 w-10 h-10">
                            <x-lucide-circle-pause class="w-5 h-5 text-yellow-700"/>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-7">
                <x-card title="Recent Categories" subtitle="The sample CRUD module used by this starter kit." class="h-100" body-class="px-0 pb-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                            <tr>
                                <th class="text-muted text-sm fw-medium ps-3">Name</th>
                                <th class="text-muted text-sm fw-medium">Status</th>
                                <th class="text-muted text-sm fw-medium text-end pe-3">Created</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentCategories as $category)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-medium">{{ $category->name }}</div>
                                    </td>
                                    <td>
                                        <x-badge :color="$category->active ? 'success' : 'warning'" size="sm">
                                            {{ $category->active ? 'Active' : 'Inactive' }}
                                        </x-badge>
                                    </td>
                                    <td class="text-muted text-sm text-end pe-3">
                                        {{ $category->created_at?->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No categories yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>

            <div class="col-12 col-xl-5">
                <div class="row g-3">
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
