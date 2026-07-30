<div class="master-row-actions">
    <button
        type="button"
        class="btn master-row-action editCategory"
        data-id="{{ $category->id }}"
        aria-label="Edit {{ $category->name }}"
    >
        <x-lucide-pencil class="w-4 h-4"/>
    </button>

    <button
        type="button"
        class="btn master-row-action master-row-action-danger deleteCategory"
        data-id="{{ $category->id }}"
        aria-label="Delete {{ $category->name }}"
    >
        <x-lucide-trash-2 class="w-4 h-4"/>
    </button>
</div>
