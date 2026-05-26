<x-card title="Recent Categories" subtitle="The sample CRUD module used by this starter kit." class="h-100" body-class="px-0 pb-0">
    <div class="overflow-auto" style="max-height: 22rem;" data-home-category-list data-url="{{ route('home.recent-categories') }}" data-page="1" aria-busy="true">
        <div class="d-grid" data-category-items></div>

        <div class="d-none text-center text-muted py-5" data-category-empty>
            <x-lucide-folder-open class="w-5 h-5 text-muted"/>
            <p class="mb-0 mt-2">No categories yet.</p>
        </div>

        <div class="p-3" data-category-loader>
            <div class="line-loader"></div>
        </div>
    </div>

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
        const $categoryList = $('[data-home-category-list]');
        const categoryItemTemplate = Handlebars.compile($('#homeCategoryItemTemplate').html());

        const loadRecentCategories = function () {
            if (!$categoryList.length || $categoryList.data('loading') === true || $categoryList.data('has-more') === false) {
                return;
            }

            const page = Number($categoryList.data('page') || 1);
            const $items = $categoryList.find('[data-category-items]');
            const $loader = $categoryList.find('[data-category-loader]');
            const $empty = $categoryList.find('[data-category-empty]');

            $categoryList.data('loading', true).attr('aria-busy', 'true');
            $loader.removeClass('d-none');

            axios.post($categoryList.data('url'), {page})
                .then((response) => {
                    const items = response.data.items || [];
                    const pagination = response.data.pagination || {};

                    items.forEach((item) => {
                        $items.append(categoryItemTemplate(item));
                    });

                    $empty.toggleClass('d-none', $items.children().length > 0);
                    $categoryList
                        .data('page', pagination.next_page || page)
                        .data('has-more', pagination.has_more === true)
                        .attr('data-page', pagination.next_page || page)
                        .attr('data-has-more', pagination.has_more === true ? 'true' : 'false');
                })
                .catch(() => {
                    toast.error('Recent categories could not be loaded.');
                })
                .finally(() => {
                    $categoryList.data('loading', false).attr('aria-busy', 'false');
                    $loader.addClass('d-none');
                });
        };

        loadRecentCategories();

        $categoryList.on('scroll', function () {
            const element = this;
            const isNearBottom = element.scrollTop + element.clientHeight >= element.scrollHeight - 40;

            if (isNearBottom) {
                loadRecentCategories();
            }
        });
    });
</script>
