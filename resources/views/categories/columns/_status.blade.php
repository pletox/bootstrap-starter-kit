<x-badge :color="$category->active ? 'success' : 'warning'" size="sm">
    {{ $category->active ? 'Active' : 'Inactive' }}
</x-badge>
