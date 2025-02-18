@if($product->active)
    <a href="#" class="badge text-bg-success toggleStatus" data-id="{{ $product->id }}" data-status="0">Active</a>
@else
    <a href="#" class="badge text-bg-danger toggleStatus" data-id="{{ $product->id }}" data-status="1">In Active</a>
@endif
