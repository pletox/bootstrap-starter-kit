@extends('layouts.developer-docs')

@section('title', 'jQuery Infinite Scroll')

@section('content')
    <div class="d-grid gap-3">
        <x-card title="When To Use This Pattern" subtitle="Use this for dashboard lists and card feeds that should append data without replacing the current scroll position.">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <x-badge color="primary">Dashboard lists</x-badge>
                    <p class="text-muted mt-2 mb-0">Recent categories, quick links, activity feeds, alerts, and small operational lists.</p>
                </div>
                <div class="col-12 col-md-4">
                    <x-badge color="success">Mobile app feel</x-badge>
                    <p class="text-muted mt-2 mb-0">Cards should sit directly in the flow, not inside a second framed card shell.</p>
                </div>
                <div class="col-12 col-md-4">
                    <x-badge color="warning">Preserve scroll</x-badge>
                    <p class="text-muted mt-2 mb-0">On create/update/delete, patch the existing DOM item instead of reloading the whole list.</p>
                </div>
            </div>
        </x-card>

        <x-card title="Async List Component" subtitle="Use the component shell so scroll state, empty state, and loader hooks stay consistent across pages.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>&lt;x-async-list max-height="18rem" :url="route('resources.index')" data-resource-list&gt;
    &lt;x-async-list.items data-resource-items/&gt;

    &lt;x-async-list.empty icon="lucide-link" data-resource-empty&gt;
        No records yet.
    &lt;/x-async-list.empty&gt;

    &lt;x-async-list.loader data-resource-loader/&gt;
&lt;/x-async-list&gt;</code></pre>
        </x-card>

        <x-card title="Item Template" subtitle="Keep only the repeated item markup in the page. The list shell belongs to x-async-list.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>&lt;script type="text/x-handlebars-template" id="resourceItemTemplate"&gt;
    &lt;div class="d-flex justify-content-between gap-3 border-bottom p-3" data-resource-id="@{{ id }}"&gt;
        &lt;div class="min-w-0"&gt;
            &lt;p class="fw-medium mb-1"&gt;@{{ title }}&lt;/p&gt;
            &lt;p class="text-muted mb-0 text-break"&gt;@{{ url }}&lt;/p&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/script&gt;</code></pre>
        </x-card>

        <x-card title="jQuery Infinite Scroll Script" subtitle="Use useAsyncList for loading, empty state, pagination state, and scroll binding.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>$(function () {
    const resources = useAsyncList('[data-resource-list]', {
        itemTemplate: '#resourceItemTemplate',
        onError: () =&gt; {
            toast.error('Records could not be loaded.');
        }
    });

    resources.load();
    resources.bindInfiniteScroll();
});</code></pre>
        </x-card>

        <x-card title="Create Or Update Without Resetting The List" subtitle="Return the item from the backend and patch the current DOM.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>form.post(route('resources.storeOrUpdate'), {
    onComplete: (response) =&gt; {
        resources.upsert(response.data.item, {
            selector: (item) =&gt; `[data-resource-id="${item.id}"]`,
            mode: 'prepend',
        });

        modal.close();
        form.reset();
    }
});

$.easyDelete({
    url: route('resources.delete', {resource: id}),
    onComplete: () =&gt; {
        resources.remove(`[data-resource-id="${id}"]`);
    }
});</code></pre>
        </x-card>

        <x-card title="Backend Endpoint" subtitle="Return predictable pagination metadata.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>public function index(Request $request): JsonResponse
{
    $resources = Resource::latest()
        -&gt;paginate(
            perPage: 5,
            columns: ['id', 'title', 'url', 'created_at'],
            page: $request-&gt;integer('page', 1),
        );

    return response()-&gt;json([
        'items' =&gt; $resources-&gt;getCollection()-&gt;map(fn (Resource $resource): array =&gt; [
            'id' =&gt; $resource-&gt;id,
            'title' =&gt; $resource-&gt;title,
            'url' =&gt; $resource-&gt;url,
            'created_at' =&gt; $resource-&gt;created_at?-&gt;diffForHumans(),
        ]),
        'pagination' =&gt; [
            'current_page' =&gt; $resources-&gt;currentPage(),
            'next_page' =&gt; $resources-&gt;hasMorePages() ? $resources-&gt;currentPage() + 1 : null,
            'has_more' =&gt; $resources-&gt;hasMorePages(),
        ],
    ]);
}</code></pre>
        </x-card>
    </div>
@endsection
