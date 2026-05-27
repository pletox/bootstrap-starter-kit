<x-modal id="tenantCreateModal" title="Create Workspace">
    <x-form method="POST" action="{{ route('tenants.store') }}">
        <x-modal.body class="space-y-3">
            <x-input label="Workspace Name" name="name" id="tenant_name" placeholder="Acme Workspace"/>
        </x-modal.body>

        <x-modal.footer>
            <x-button color="light" data-bs-dismiss="modal">Cancel</x-button>
            <x-button type="submit" color="primary">Create</x-button>
        </x-modal.footer>
    </x-form>
</x-modal>