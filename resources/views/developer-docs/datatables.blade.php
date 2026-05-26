@extends('layouts.developer-docs')

@section('title', 'DataTables')

@section('content')
    <div class="d-grid gap-3">
        <x-card title="Standard DataTable" subtitle="Use jpDataTable for server-side tables, filters, mobile cards, and bulk actions.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>let table = $('#categories-table').jpDataTable({
    url: route('categories.index'),
    columns: [
        {data: 'select', name: 'select', orderable: false, searchable: false},
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'name', name: 'name'},
        {data: 'status', name: 'active'},
        {data: 'description', name: 'description'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    filters: ['#categorySearch', '#categoryStatusFilter', '#categoryCreatedFrom', '#categoryCreatedTo']
});</code></pre>
        </x-card>

        <x-card title="Filters" subtitle="Filter inputs should be normal kit components.">
            <pre class="bg-dark text-white rounded p-3 mb-3"><code>&lt;x-input id="categorySearch" name="q" label="Search"/&gt;
&lt;x-select2 id="categoryStatusFilter" name="active" label="Status"&gt;
    &lt;option value="1"&gt;Active&lt;/option&gt;
    &lt;option value="0"&gt;Inactive&lt;/option&gt;
&lt;/x-select2&gt;
&lt;x-datepicker id="categoryCreatedFrom" name="created_from" format="Y-m-d"/&gt;</code></pre>

            <pre class="bg-dark text-white rounded p-3 mb-0"><code>$('#applyCategoryFilters').on('click', function () {
    table.draw();
});

$('#resetCategoryFilters').on('click', function () {
    $('#categorySearch, #categoryCreatedFrom, #categoryCreatedTo').val('');
    $('#categoryStatusFilter').val(null).trigger('change');
    table.draw();
});</code></pre>
        </x-card>

        <x-card title="Mobile Cards" subtitle="Keep card rendering generic through the mobileCards option.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>mobileCards: {
    enabled: true,
    breakpoint: 768,
    pageLength: 8,
    renderCard: function (row) {
        return `
            &lt;div class="jp-mobile-card"&gt;
                &lt;div class="d-flex align-items-start justify-content-between gap-3"&gt;
                    &lt;div class="d-flex align-items-start gap-2 min-w-0"&gt;
                        &lt;input type="checkbox" class="form-check-input row-select mt-1" value="${row.id}"&gt;
                        &lt;div class="min-w-0"&gt;
                            &lt;div class="fw-semibold text-truncate"&gt;${row.name}&lt;/div&gt;
                            &lt;div class="text-muted text-sm mt-1"&gt;${row.description || ''}&lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    ${row.action}
                &lt;/div&gt;
            &lt;/div&gt;
        `;
    }
}</code></pre>
        </x-card>

        <x-card title="Server Response" subtitle="Return DataTables-compatible JSON and count after filters.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>$query = Category::query()
    -&gt;when($request-&gt;filled('q'), function ($query) use ($request) {
        $query-&gt;where('name', 'like', '%' . $request-&gt;q . '%');
    });

$recordsFiltered = $query-&gt;clone()-&gt;count();
$recordsTotal = Category::count();

$rows = $query
    -&gt;skip($request-&gt;integer('start'))
    -&gt;take($request-&gt;integer('length', 10))
    -&gt;get();

return DataTables::of($rows)
    -&gt;with([
        'recordsTotal' =&gt; $recordsTotal,
        'recordsFiltered' =&gt; $recordsFiltered,
    ])
    -&gt;skipPaging()
    -&gt;make(true);</code></pre>
        </x-card>
    </div>
@endsection
