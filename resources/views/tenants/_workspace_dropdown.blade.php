@php
    $currentTenant = \App\Support\Tenancy::currentTenant();
    $tenants = auth()->user()?->tenants()->orderBy('name')->get() ?? collect();
@endphp

<div class="sidebar-logo dropdown px-4">
    <button type="button"
            class="btn w-100 h-100 justify-content-between text-start px-0 py-0 shadow-none border-0 bg-transparent"
            data-bs-toggle="dropdown"
            data-bs-display="static"
            aria-expanded="false">
        <span class="d-flex align-items-center gap-3 min-w-0">
            <span class="d-flex align-items-center justify-content-center overflow-hidden rounded-2 w-8 h-8 bg-black flex-shrink-0">
                <x-lucide-building-2 class="w-5 h-5 text-white"/>
            </span>
            <span class="d-grid min-w-0">
                <span class="text-truncate fw-semibold">{{ $currentTenant?->name ?? 'Select workspace' }}</span>
                <span class="text-muted text-xs text-truncate">Workspace</span>
            </span>
        </span>
        <x-lucide-chevron-down class="w-4 h-4 text-muted flex-shrink-0"/>
    </button>

    <div class="dropdown-menu dropdown-menu-start dropdown-modern rounded-3 shadow-sm border p-2 mt-2" style="width: min(15rem, calc(100vw - 1.5rem));">
        <div class="px-2 pb-2 text-xs text-muted text-uppercase fw-semibold">Workspaces</div>

        @forelse($tenants as $tenant)
            <form method="POST" action="{{ route('tenants.switch', $tenant) }}">
                @csrf
                <button type="submit" class="dropdown-item rounded-2 d-flex align-items-center justify-content-between gap-2 px-2 py-2 {{ $currentTenant?->is($tenant) ? 'active bg-primary-subtle text-primary' : '' }}">
                    <span class="d-flex align-items-center gap-2 min-w-0">
                        <span class="d-flex align-items-center justify-content-center rounded-2 bg-body-secondary w-7 h-7 flex-shrink-0">
                            <x-lucide-building-2 class="w-4 h-4"/>
                        </span>
                        <span class="text-truncate fw-semibold">{{ $tenant->name }}</span>
                    </span>
                    @if($currentTenant?->is($tenant))
                        <x-lucide-check class="w-4 h-4 flex-shrink-0"/>
                    @endif
                </button>
            </form>
        @empty
            <div class="px-2 py-2 text-muted text-sm">No workspaces yet.</div>
        @endforelse

        <div class="dropdown-divider my-2"></div>

        <button type="button" class="dropdown-item rounded-2 d-flex align-items-center gap-2 px-2 py-2 fw-semibold text-primary" data-bs-toggle="modal" data-bs-target="#tenantCreateModal">
            <x-lucide-plus class="w-4 h-4"/>
            <span>Create workspace</span>
        </button>
    </div>
</div>
