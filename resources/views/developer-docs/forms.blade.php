@extends('layouts.developer-docs')

@section('title', 'Forms And AJAX CRUD')

@section('content')
    <div class="d-grid gap-3">
        <x-card title="AJAX CRUD Pattern" subtitle="Use useForm, useModal, and backend JSON responses.">
            <pre class="bg-dark text-white rounded p-3 mb-3"><code>$(function () {
    const form = useForm('#resourceForm');
    const modal = useModal('#resourceModal');
    const table = $('#resource-table').data('jp-datatable-instance');

    $('#add-resource-btn').on('click', function () {
        form.reset();
        modal.open('Create Resource');
    });

    $('#resourceForm').on('submit', function (event) {
        event.preventDefault();

        form.post(route('resources.storeOrUpdate'), {
            onComplete: (response) => {
                toast.success(response.data.message);
                modal.close();
                table.draw(false);
            }
        });
    });

    $('body').on('click', '.editResource', function () {
        axios.get(route('resources.edit', {resource: $(this).data('id')}))
            .then((response) => {
                form.fill(response.data);
                modal.open('Edit Resource');
            });
    });
});</code></pre>

            <p class="text-muted mb-0">For list UIs that should preserve scroll position, return the saved item from the backend and update the item in place instead of resetting the full list.</p>
        </x-card>

        <x-card title="Select2 API Fields" subtitle="API-backed Select2 fields hydrate missing values for form.fill and x-model.">
            <div class="row g-3">
                <div class="col-12 col-lg-6">
                    <h2 class="h6">Blade</h2>
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>&lt;x-select2
    id="category"
    name="category_id"
    label="Category"
    placeholder="Search categories"
    :api-url="route('categories.options')"
    x-model="categoryId"
/&gt;</code></pre>
                </div>
                <div class="col-12 col-lg-6">
                    <h2 class="h6">Response</h2>
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>{
  "results": [
    { "id": 1, "text": "Hardware" }
  ],
  "pagination": {
    "more": false
  }
}</code></pre>
                </div>
            </div>
        </x-card>

        <x-card title="Controller Validation" subtitle="Write only validated data.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>public function storeOrUpdate(Request $request): JsonResponse
{
    $validated = $request->validate([
        'id' => ['nullable', 'integer', 'exists:categories,id'],
        'name' => ['required', 'string', 'min:2', 'max:120'],
        'description' => ['nullable', 'string'],
        'active' => ['required', 'boolean'],
    ]);

    $category = Category::updateOrCreate(
        ['id' => $validated['id'] ?? null],
        Arr::except($validated, ['id']),
    );

    return response()->json([
        'message' => 'Category saved successfully.',
        'item' => $category,
    ]);
}</code></pre>
        </x-card>
    </div>
@endsection
