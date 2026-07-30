<span class="master-row-meta category-row-description">
    {{ \Illuminate\Support\Str::limit(strip_tags((string) $category->description), 120) ?: 'No description' }}
</span>
