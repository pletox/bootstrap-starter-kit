<div class="d-flex align-items-center gap-1">
    <div class="dropdown">
        <x-button color="light" class="btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
            <x-lucide-ellipsis class="w-3 h-3"/>
        </x-button>
        <ul class="dropdown-menu dropdown-modern rounded-2">
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2 px-3 py-1.5 rounded-1 editCategory"
                   href="javascript:void(0)"
                   data-id="{{ $category->id }}">
                    <x-lucide-pencil class="w-4 h-4 text-muted"/>
                    <span>Edit</span>
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2 px-3 py-1.5 rounded-1 text-danger deleteCategory"
                   href="javascript:void(0)"
                   data-id="{{ $category->id }}">
                    <x-lucide-trash class="w-4 h-4"/>
                    <span>Delete</span>
                </a>
            </li>
        </ul>
    </div>

</div>
