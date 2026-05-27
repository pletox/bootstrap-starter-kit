@extends('layouts.developer-docs')

@section('title', 'Backend Patterns')

@section('content')
    <div class="d-grid gap-3">
        <x-card title="Routes" subtitle="Keep example/dev routes local-only and real app routes authenticated.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>Route::group(['middleware' => ['auth:web']], function () {
    Route::get('resources', [ResourcesController::class, 'index'])->name('resources.index');
    Route::post('resources', [ResourcesController::class, 'storeOrUpdate'])->name('resources.storeOrUpdate');
    Route::get('resources/options', [ResourcesController::class, 'options'])->name('resources.options');
    Route::get('resources/{resource}', [ResourcesController::class, 'edit'])->name('resources.edit');
    Route::delete('resources/{resource}', [ResourcesController::class, 'destroy'])->name('resources.delete');
});</code></pre>
        </x-card>

        <x-card title="Controller Shape" subtitle="Small methods, explicit return types, validated writes.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>public function options(Request $request): JsonResponse
{
    $resources = Resource::query()
        -&gt;when($request-&gt;filled('q'), function ($query) use ($request) {
            $query-&gt;where('name', 'like', '%' . $request-&gt;q . '%');
        })
        -&gt;when($request-&gt;filled('id'), function ($query) use ($request) {
            $query-&gt;whereIn('id', (array) $request-&gt;id);
        })
        -&gt;orderBy('name')
        -&gt;paginate(10);

    return response()-&gt;json([
        'results' =&gt; $resources-&gt;getCollection()-&gt;map(fn ($resource) =&gt; [
            'id' =&gt; $resource-&gt;id,
            'text' =&gt; $resource-&gt;name,
        ]),
        'pagination' =&gt; [
            'more' =&gt; $resources-&gt;hasMorePages(),
        ],
    ]);
}</code></pre>
        </x-card>

        <x-card title="Model And Factory" subtitle="Every sample module should be testable with factories.">
            <div class="row g-3">
                <div class="col-12 col-lg-6">
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>class Resource extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}</code></pre>
                </div>
                <div class="col-12 col-lg-6">
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>public function definition(): array
{
    return [
        'name' => fake()->company(),
        'active' => true,
    ];
}</code></pre>
                </div>
            </div>
        </x-card>

        <x-card title="Tenant Workspaces" subtitle="Use the starter kit command when an app needs user-owned workspaces and tenant-scoped data.">
            <div class="d-grid gap-3">
                <p class="text-muted mb-0">The command adds a <code>Tenant</code> model, tenant/user pivot, current tenant tracking, a registration workspace field, registration workspace creation, sidebar workspace switching, switch/create routes, and tenant scoping for selected models.</p>

                <div>
                    <h3 class="h6 mb-2">Install</h3>
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>php artisan starter-kit:tenancy install
php artisan migrate</code></pre>
                </div>

                <div>
                    <h3 class="h6 mb-2">Scope More Tables</h3>
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>php artisan starter-kit:tenancy install \
    --tables=categories,quick_links,projects \
    --models=Category,QuickLink,Project</code></pre>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="border rounded p-3 h-100">
                            <h3 class="h6 mb-2">Generated Backend</h3>
                            <ul class="text-muted mb-0">
                                <li><code>Tenant</code> model and <code>tenant_user</code> pivot.</li>
                                <li><code>current_tenant_id</code> on users.</li>
                                <li><code>BelongsToTenant</code> model concern.</li>
                                <li>Create and switch tenant routes.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="border rounded p-3 h-100">
                            <h3 class="h6 mb-2">Generated UI</h3>
                            <ul class="text-muted mb-0">
                                <li>Workspace name field on registration.</li>
                                <li>Workspace created during registration.</li>
                                <li>Sidebar workspace dropdown.</li>
                                <li>Create workspace modal.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="h6 mb-2">Remove</h3>
                    <p class="text-muted mb-2">Before reversing, roll back the generated migrations or create a new migration that removes the tenancy database changes. The command asks you to confirm this first.</p>
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>php artisan starter-kit:tenancy remove</code></pre>
                </div>
            </div>
        </x-card>

        <x-card title="Security Checklist" subtitle="Small habits that prevent large mistakes.">
            <ul class="text-muted mb-0">
                <li>Validate all incoming fields and write only validated data.</li>
                <li>Never use raw SQL with user input.</li>
                <li>Use route model binding for edit/delete endpoints.</li>
                <li>Paginate list endpoints and select only columns you need.</li>
                <li>Keep local docs and UI Kit routes hidden outside local environments.</li>
            </ul>
        </x-card>
    </div>
@endsection
