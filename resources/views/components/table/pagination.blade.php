@props([
    'table' => 'table', // Alpine table instance name
    'size' => 'sm' // sm | md | lg
])

@php
    $sizeClass = $size === 'sm' ? 'pagination-sm' : ($size === 'lg' ? 'pagination-lg' : '');
@endphp

<nav aria-label="Table pagination" class="mt-1">
    <ul class="pagination {{ $sizeClass }} justify-content-end mb-0">
        <!-- Previous -->
        <li class="page-item" :class="{ 'disabled': {{ $table }}.page <= 1 }">
            <a href="#" class="page-link" aria-label="Previous"
               @click.prevent="{{ $table }}.goToPage({{ $table }}.page - 1)">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <!-- Page Numbers -->
        <template x-for="p in {{ $table }}.pages" :key="p">
            <li class="page-item" :class="{ 'active': p === {{ $table }}.page }">
                <a href="#" class="page-link" @click.prevent="{{ $table }}.goToPage(p)" x-text="p"></a>
            </li>
        </template>

        <!-- Next -->
        <li class="page-item" :class="{ 'disabled': {{ $table }}.page >= {{ $table }}.totalPages }">
            <a href="#" class="page-link" aria-label="Next"
               @click.prevent="{{ $table }}.goToPage({{ $table }}.page + 1)">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
