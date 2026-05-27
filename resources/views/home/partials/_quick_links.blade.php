<x-card class="h-100" body-class="px-0 pb-0">
    <x-slot:header>
        <div class="d-flex align-items-center justify-content-between gap-3">
            <h5 class="card-title mb-0">Quick Links</h5>

            <button type="button" class="btn btn-link text-danger fw-semibold text-decoration-none d-inline-flex align-items-center gap-2 p-0" data-quick-link-add>
                <x-lucide-plus class="w-5 h-5"/>
                <span>Add Link</span>
            </button>
        </div>
    </x-slot:header>

    <x-async-list max-height="18rem" :url="route('quick-links.index')" data-quick-link-list>
        <x-async-list.items data-quick-link-items/>

        <x-async-list.empty icon="lucide-link" data-quick-link-empty>
            No quick links yet.
        </x-async-list.empty>

        <x-async-list.loader data-quick-link-loader/>
    </x-async-list>
</x-card>

<x-modal id="quickLinkModal" title="Add Quick Link">
    <x-form id="quickLinkForm">
        <x-modal.body class="space-y-3">
            <input type="hidden" name="id"/>
            <x-input name="title" label="Title" placeholder="Example: Connect to Forge DB"/>
            <x-input name="url" label="URL" placeholder="https://example.com"/>
        </x-modal.body>

        <x-modal.footer>
            <x-button color="light" data-bs-dismiss="modal">Cancel</x-button>
            <x-button color="dark" type="submit">Save Link</x-button>
        </x-modal.footer>
    </x-form>
</x-modal>

<script type="text/x-handlebars-template" id="quickLinkItemTemplate">
    <div class="d-flex align-items-center justify-content-between gap-3 border-bottom p-3" data-quick-link-id="@{{ id }}">
        <div class="min-w-0">
            <a href="@{{ url }}" target="_blank" rel="noopener noreferrer" class="d-block text-body fw-medium text-decoration-none fs-6">@{{ title }}</a>
            <a href="@{{ url }}" target="_blank" rel="noopener noreferrer" class="d-flex align-items-start gap-2 mt-2 text-muted text-decoration-none text-break">
                <i class="bi bi-arrow-return-right flex-shrink-0 mt-1"></i>
                <span class="text-break">@{{ url }}</span>
            </a>
        </div>

        <div class="d-flex flex-shrink-0 gap-3">
            <button type="button" class="btn btn-link text-danger p-0" data-quick-link-edit="@{{ id }}" aria-label="Edit @{{ title }}">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-link text-danger p-0" data-quick-link-delete="@{{ id }}" aria-label="Delete @{{ title }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</script>

<script type="module">
    $(function () {
        const quickLinks = useAsyncList('[data-quick-link-list]', {
            itemTemplate: '#quickLinkItemTemplate',
            onError: () => {
                toast.error('Quick links could not be loaded.');
            }
        });
        const quickLinkForm = useForm('#quickLinkForm');
        const quickLinkModal = useModal('#quickLinkModal');

        quickLinks.load();
        quickLinks.bindInfiniteScroll();

        $('[data-quick-link-add]').on('click', function () {
            quickLinkForm.reset();
            quickLinkModal.open('Add Quick Link');
        });

        $('#quickLinkForm').on('submit', function (e) {
            e.preventDefault();

            quickLinkForm.post(route('quick-links.storeOrUpdate'), {
                onComplete: (response) => {
                    const item = response.data.item;

                    quickLinks.upsert(item, {
                        selector: (item) => `[data-quick-link-id="${item.id}"]`,
                    });

                    quickLinkModal.close();
                    quickLinkForm.reset();
                },
            });
        });

        $('body').on('click', '[data-quick-link-edit]', function () {
            const id = $(this).data('quick-link-edit');

            axios.get(route('quick-links.edit', {quickLink: id})).then((response) => {
                quickLinkForm.fill(response.data);
                quickLinkModal.open('Edit Quick Link');
            });
        });

        $('body').on('click', '[data-quick-link-delete]', function () {
            const id = $(this).data('quick-link-delete');

            $.easyDelete({
                url: route('quick-links.delete', {quickLink: id}),
                confirmationMessage: 'Do you really want to delete this quick link?',
                onComplete: () => {
                    quickLinks.remove(`[data-quick-link-id="${id}"]`);
                },
            });
        });
    });
</script>
