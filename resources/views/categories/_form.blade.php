<x-modal id="categoryModal" title="Create Category">
    <x-form id="categoryForm">
        <x-modal.body class="space-y-3">
            <input type="hidden" name="id" />
            <x-input  name="name" label="Name" placeholder="Enter name"/>
            <x-richtext id="description" name="description" label="Description" placeholder="Enter description"/>

            <x-select2 id="status" name="active" label="Status" placeholder="Select Status">
                <option value="1">Active</option>
                <option value="0">In Active</option>
            </x-select2>
        </x-modal.body>

        <x-modal.footer>
            <x-button color="light" data-bs-dismiss="modal">Cancel</x-button>
            <x-button color="primary" type="submit">Submit</x-button>
        </x-modal.footer>
    </x-form>
</x-modal>
