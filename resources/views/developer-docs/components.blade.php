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
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card title="Good Form Markup" subtitle="Keep pages readable and component-first.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>&lt;x-form id="resourceForm"&gt;
    &lt;x-modal.body class="space-y-3"&gt;
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
&lt;/x-form&gt;</code></pre>
        </x-card>

        <x-card title="When To Add A New Component" subtitle="Add a component when the behavior is reusable, not just because markup is long.">
            <ul class="text-muted mb-0">
                <li>Add a component when markup appears in multiple modules.</li>
                <li>Add a component when initialization must work with <code>wire:navigate</code>.</li>
                <li>Add a component when <code>form.fill()</code>, <code>x-model</code>, or validation state must be supported.</li>
                <li>Do not add custom component classes for one-off spacing or layout. Use Bootstrap utilities.</li>
            </ul>
        </x-card>
    </div>
@endsection
