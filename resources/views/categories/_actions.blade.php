<div class="d-flex align-items-center gap-1">
    <x-button color="dark" class="btn-sm editCategory" data-toggle="tooltip" data-id="{{ $category->id }}"
              data-original-title="Edit">
        <x-lucide-pencil class="w-3 h-3"/>
    </x-button>

    <x-button color="danger" class="btn-sm deleteCategory" data-toggle="tooltip" data-id="{{ $category->id }}"
              data-original-title="Delete">
        <x-lucide-trash class="w-3 h-3"/>
    </x-button>
</div>
