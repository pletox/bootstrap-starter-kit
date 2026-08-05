<x-card title="Recent Categories" subtitle="The sample CRUD module used by this starter kit." class="h-100" body-class="px-0 pb-0">
    <x-async-list max-height="22rem" :url="route('home.recent-categories')" data-home-category-list>
        <x-async-list.items class="d-grid" data-category-items/>

        <x-async-list.empty icon="lucide-folder-open" data-category-empty>
            No categories yet.
        </x-async-list.empty>

        <x-async-list.loader data-category-loader/>
    </x-async-list>

    <script type="text/x-handlebars-template" id="homeCategoryItemTemplate">
        <div class="border-bottom p-3">
            <div class="d-flex align-items-start gap-3">
                <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded bg-gray-100 text-gray-700 w-8 h-8">
                    <i class="bi bi-folder"></i>
                </div>
                <div class="min-w-0 flex-grow-1">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                        <p class="mb-0 fw-medium text-truncate">@{{ name }}</p>
                        <span class="badge bg-@{{ status_color }} text-white rounded-pill px-2 py-1 small align-self-start align-self-sm-center">
                            @{{ status }}
                        </span>
                    </div>
                    <p class="mb-0 text-muted text-sm">@{{ created_at }}</p>
                </div>
            </div>
        </div>
    </script>
</x-card>

<script type="module">
    $(function () {
        const recentCategories = useAsyncList('[data-home-category-list]', {
            itemTemplate: '#homeCategoryItemTemplate',
            onError: () => {
                toast.error('Recent categories could not be loaded.');
            }
        });

        recentCategories.load();
        recentCategories.bindInfiniteScroll();
    });
</script>
