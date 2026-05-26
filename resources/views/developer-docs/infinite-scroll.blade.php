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

        <x-card title="Blade Markup" subtitle="Keep the partial self-contained with list, empty, loader, modal, template, and script.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>&lt;div class="overflow-auto" style="max-height: 18rem;"
     data-resource-list
     data-url="&#123;&#123; route('resources.index') &#125;&#125;"
     data-page="1"
     aria-busy="true"&gt;
    &lt;div data-resource-items&gt;&lt;/div&gt;

    &lt;div class="d-none text-center text-muted py-5" data-resource-empty&gt;
        No records yet.
    &lt;/div&gt;

    &lt;div class="p-3" data-resource-loader&gt;
        &lt;div class="line-loader"&gt;&lt;/div&gt;
    &lt;/div&gt;
&lt;/div&gt;

&lt;script type="text/x-handlebars-template" id="resourceItemTemplate"&gt;
    &lt;div class="d-flex justify-content-between gap-3 border-bottom p-3" data-resource-id="@{{ id }}"&gt;
        &lt;div class="min-w-0"&gt;
            &lt;p class="fw-medium mb-1"&gt;@{{ title }}&lt;/p&gt;
            &lt;p class="text-muted mb-0 text-break"&gt;@{{ url }}&lt;/p&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/script&gt;</code></pre>
        </x-card>

        <x-card title="jQuery Infinite Scroll Script" subtitle="Throttle with loading and has-more flags, then append Handlebars rows.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>$(function () {
    const $list = $('[data-resource-list]');
    const template = Handlebars.compile($('#resourceItemTemplate').html());

    const loadItems = function () {
        if (!$list.length || $list.data('loading') === true || $list.data('has-more') === false) {
            return;
        }

        const page = Number($list.data('page') || 1);
        const $items = $list.find('[data-resource-items]');
        const $loader = $list.find('[data-resource-loader]');
        const $empty = $list.find('[data-resource-empty]');

        $list.data('loading', true).attr('aria-busy', 'true');
        $loader.removeClass('d-none');

        axios.post($list.data('url'), {page})
            .then((response) =&gt; {
                const items = response.data.items || [];
                const pagination = response.data.pagination || {};

                items.forEach((item) =&gt; {
                    $items.append(template(item));
                });

                $empty.toggleClass('d-none', $items.children().length &gt; 0);
                $list
                    .data('page', pagination.next_page || page)
                    .data('has-more', pagination.has_more === true);
            })
            .catch(() =&gt; {
                toast.error('Records could not be loaded.');
            })
            .finally(() =&gt; {
                $list.data('loading', false).attr('aria-busy', 'false');
                $loader.addClass('d-none');
            });
    };

    loadItems();

    $list.on('scroll', function () {
        const nearBottom = this.scrollTop + this.clientHeight &gt;= this.scrollHeight - 40;

        if (nearBottom) {
            loadItems();
        }
    });
});</code></pre>
        </x-card>

        <x-card title="Create Or Update Without Resetting The List" subtitle="Return the item from the backend and patch the current DOM.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>form.post(route('resources.storeOrUpdate'), {
    onComplete: (response) =&gt; {
        const item = response.data.item;
        const rendered = template(item);
        const $items = $list.find('[data-resource-items]');
        const $existing = $items.find(`[data-resource-id="${item.id}"]`);

        if ($existing.length) {
            $existing.replaceWith(rendered);
        } else {
            $items.prepend(rendered);
        }

        $list.find('[data-resource-empty]').addClass('d-none');
        modal.close();
        form.reset();
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
