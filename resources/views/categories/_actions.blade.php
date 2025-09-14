<div class="d-flex align-items-center gap-1">
    <x-dropdown align="end" color="light" icon="lucide-ellipsis" buttonClass="btn-sm">
        <x-dropdown.header>Manage</x-dropdown.header>

        <x-dropdown.item
            icon="lucide-edit"
            class="editCategory"
            data-id="{{ $category->id }}"
        >
            Edit
        </x-dropdown.item>

        <x-dropdown.item
            icon="lucide-trash"
            class="text-danger deleteCategory"
            data-id="{{ $category->id }}"
        >
            Delete
        </x-dropdown.item>
    </x-dropdown>

</div>
