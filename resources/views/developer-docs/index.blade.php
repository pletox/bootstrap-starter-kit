@extends('layouts.developer-docs')

@section('title', 'Developer Docs')

@section('content')
    <div class="d-grid gap-3">
        <x-card title="How To Build In This Kit" subtitle="Local-only implementation notes for extending the starter kit without drifting from its patterns.">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Bootstrap utilities first</h2>
                        <p class="text-muted mb-0">Use Bootstrap grid, spacing, flex, border, overflow, typography, and responsive utilities before creating Sass. Add custom CSS only for shared behavior or visual rules that utilities cannot express cleanly.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">jQuery for app behavior</h2>
                        <p class="text-muted mb-0">Prefer jQuery selectors, delegated events, plugins, and Axios flows. Keep page scripts small and move reusable behavior into <code>resources/js/extendJquery.js</code>.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Components own widgets</h2>
                        <p class="text-muted mb-0">Use Blade components for Select2, rich text, date pickers, buttons, cards, tables, modals, and form controls. Do not initialize form widgets directly in page scripts.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Reference modules matter</h2>
                        <p class="text-muted mb-0">Categories is the CRUD reference. Dashboard is the AJAX partial/list reference. UI Kit is the component reference. Keep these examples realistic and production-shaped.</p>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card title="Recommended Build Order" subtitle="Use this sequence when adding a new resource module.">
            <div class="list-group list-group-flush">
                <div class="list-group-item px-0 d-flex gap-3">
                    <x-badge color="dark">1</x-badge>
                    <div>
                        <p class="fw-medium mb-1">Create backend pieces</p>
                        <p class="text-muted mb-0">Migration, model, factory, controller, routes, validation, and tests. Keep controller writes limited to validated data.</p>
                    </div>
                </div>
                <div class="list-group-item px-0 d-flex gap-3">
                    <x-badge color="dark">2</x-badge>
                    <div>
                        <p class="fw-medium mb-1">Build the Blade surface</p>
                        <p class="text-muted mb-0">Use <code>x-card</code>, <code>x-button</code>, <code>x-form</code>, <code>x-input</code>, <code>x-select2</code>, and <code>x-modal</code>.</p>
                    </div>
                </div>
                <div class="list-group-item px-0 d-flex gap-3">
                    <x-badge color="dark">3</x-badge>
                    <div>
                        <p class="fw-medium mb-1">Wire jQuery behavior</p>
                        <p class="text-muted mb-0">Use <code>useForm()</code>, <code>useModal()</code>, <code>jpDataTable()</code>, delegated events, and shared helpers.</p>
                    </div>
                </div>
                <div class="list-group-item px-0 d-flex gap-3">
                    <x-badge color="dark">4</x-badge>
                    <div>
                        <p class="fw-medium mb-1">Verify the flow</p>
                        <p class="text-muted mb-0">Run focused Pest tests and <code>npm run build</code>. Add UI Kit examples for new reusable component behavior.</p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
@endsection
