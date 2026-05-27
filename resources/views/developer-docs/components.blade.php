@extends('layouts.developer-docs')

@section('title', 'Component Guidelines')

@section('content')
    <div class="d-grid gap-3">
        <x-card title="Component Rules" subtitle="Use Blade components for shared UI and widget initialization.">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Need</th>
                        <th>Use</th>
                        <th>Avoid</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Action buttons</td>
                        <td><code>&lt;x-button color="dark"&gt;</code></td>
                        <td>Raw <code>&lt;button class="btn..."&gt;</code> in pages.</td>
                    </tr>
                    <tr>
                        <td>Dropdown actions</td>
                        <td><code>&lt;x-dropdown&gt;</code> and <code>&lt;x-dropdown.item&gt;</code></td>
                        <td>Custom action menus per module.</td>
                    </tr>
                    <tr>
                        <td>Select2 field</td>
                        <td><code>&lt;x-select2&gt;</code></td>
                        <td>Page scripts calling <code>jpSelect2()</code>.</td>
                    </tr>
                    <tr>
                        <td>Rich text field</td>
                        <td><code>&lt;x-richtext&gt;</code></td>
                        <td>Page scripts calling <code>jpEditor()</code>.</td>
                    </tr>
                    <tr>
                        <td>Date field</td>
                        <td><code>&lt;x-datepicker format="Y-m-d"&gt;</code></td>
                        <td>Plain date inputs for shared form/filter UI.</td>
                    </tr>
                    <tr>
                        <td>Page title and actions</td>
                        <td><code>&lt;x-page-header&gt;</code></td>
                        <td>Repeating flex title/action wrappers in pages.</td>
                    </tr>
                    <tr>
                        <td>Dashboard counters</td>
                        <td><code>&lt;x-stat-card&gt;</code></td>
                        <td>Copying card/icon/counter markup per metric.</td>
                    </tr>
                    <tr>
                        <td>Async list shell</td>
                        <td><code>&lt;x-async-list&gt;</code> with nested items, empty, and loader components</td>
                        <td>Repeating scroll container, empty state, and loader markup.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card title="Page Header" subtitle="Use this for top-of-page title, description, and action buttons.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>&lt;x-page-header
    title="Manage Categories"
    subtitle="Create, edit, filter, and export records."
&gt;
    &lt;x-slot:actions&gt;
        &lt;x-button color="light" data-bs-toggle="collapse" data-bs-target="#filters"&gt;
            &lt;x-lucide-sliders-horizontal class="w-4 h-4"/&gt;
            &lt;span&gt;Filters&lt;/span&gt;
        &lt;/x-button&gt;
        &lt;x-button color="dark" id="add-record-btn"&gt;
            &lt;x-lucide-plus class="w-4 h-4"/&gt;
            &lt;span&gt;Add Record&lt;/span&gt;
        &lt;/x-button&gt;
    &lt;/x-slot:actions&gt;
&lt;/x-page-header&gt;</code></pre>
        </x-card>

        <x-card title="Stat Cards And Empty States" subtitle="Use small primitives for repeated dashboard and list states.">
            <pre class="bg-dark text-white rounded p-3 mb-3"><code>&lt;x-stat-card
    label="Active Records"
    icon="lucide-circle-check"
    icon-bg="bg-green-100"
    icon-class="w-5 h-5 text-green-700"
    loading
    data-home-counter="active"
    aria-busy="true"
/&gt;</code></pre>

            <pre class="bg-dark text-white rounded p-3 mb-0"><code>&lt;x-empty-state
    class="d-none"
    icon="lucide-folder-open"
    title="No categories yet."
    data-category-empty
/&gt;</code></pre>
        </x-card>

        <x-card title="Good Form Markup" subtitle="Keep pages readable and component-first. Use modal wrappers only when the form lives inside a modal.">
            <div class="row g-3">
                <div class="col-12 col-xl-6">
                    <h2 class="h6">Page Form</h2>
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>&lt;x-form id="resourceForm" class="d-grid gap-3"&gt;
    &lt;input type="hidden" name="id"/&gt;
    &lt;x-input name="name" label="Name" placeholder="Enter name"/&gt;
    &lt;x-select2 name="active" label="Status" placeholder="Select status"&gt;
        &lt;option value="1"&gt;Active&lt;/option&gt;
        &lt;option value="0"&gt;Inactive&lt;/option&gt;
    &lt;/x-select2&gt;
    &lt;x-richtext name="description" label="Description"/&gt;

    &lt;div class="d-flex justify-content-end gap-2"&gt;
        &lt;x-button color="light" type="reset"&gt;Reset&lt;/x-button&gt;
        &lt;x-button color="dark" type="submit"&gt;Save&lt;/x-button&gt;
    &lt;/div&gt;
&lt;/x-form&gt;</code></pre>
                </div>
                <div class="col-12 col-xl-6">
                    <h2 class="h6">Modal Form</h2>
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>&lt;x-modal id="resourceModal" title="Create Resource"&gt;
    &lt;x-form id="resourceForm"&gt;
        &lt;x-modal.body class="d-grid gap-3"&gt;
            &lt;input type="hidden" name="id"/&gt;
            &lt;x-input name="name" label="Name" placeholder="Enter name"/&gt;
            &lt;x-select2 name="active" label="Status" placeholder="Select status"&gt;
                &lt;option value="1"&gt;Active&lt;/option&gt;
                &lt;option value="0"&gt;Inactive&lt;/option&gt;
            &lt;/x-select2&gt;
            &lt;x-richtext name="description" label="Description"/&gt;
        &lt;/x-modal.body&gt;

        &lt;x-modal.footer&gt;
            &lt;x-button color="light" data-bs-dismiss="modal"&gt;Cancel&lt;/x-button&gt;
            &lt;x-button color="dark" type="submit"&gt;Save&lt;/x-button&gt;
        &lt;/x-modal.footer&gt;
    &lt;/x-form&gt;
&lt;/x-modal&gt;</code></pre>
                </div>
            </div>
        </x-card>

        <x-card title="When To Add A New Component" subtitle="Add a component when the behavior is reusable, not just because markup is long.">
            <div class="developer-docs-checklist">
                <div class="developer-docs-checklist-item">
                    <span class="developer-docs-checklist-icon is-positive">
                        <x-lucide-check class="w-4 h-4"/>
                    </span>
                    <p class="mb-0">Markup appears in multiple modules.</p>
                </div>
                <div class="developer-docs-checklist-item">
                    <span class="developer-docs-checklist-icon is-positive">
                        <x-lucide-check class="w-4 h-4"/>
                    </span>
                    <p class="mb-0">Initialization must work with <code>wire:navigate</code>.</p>
                </div>
                <div class="developer-docs-checklist-item">
                    <span class="developer-docs-checklist-icon is-positive">
                        <x-lucide-check class="w-4 h-4"/>
                    </span>
                    <p class="mb-0"><code>form.fill()</code>, <code>x-model</code>, or validation state must be supported.</p>
                </div>
                <div class="developer-docs-checklist-item">
                    <span class="developer-docs-checklist-icon is-negative">
                        <x-lucide-x class="w-4 h-4"/>
                    </span>
                    <p class="mb-0">Do not add custom component classes for one-off spacing or layout. Use Bootstrap utilities.</p>
                </div>
            </div>
        </x-card>
    </div>
@endsection
