@extends('layouts.developer-docs')

@section('title', 'Testing And Verification')

@section('content')
    <div class="d-grid gap-3">
        <x-card title="What To Test" subtitle="Test behavior, not implementation details.">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">CRUD endpoints</h2>
                        <p class="text-muted mb-0">Create, update, edit JSON, delete, export, bulk actions, and validation failures.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">AJAX lists</h2>
                        <p class="text-muted mb-0">Counters, paginated dashboard lists, quick links, and infinite-scroll pagination metadata.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Local-only pages</h2>
                        <p class="text-muted mb-0">UI Kit and Developer Docs should return 200 locally and 404 outside local.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h2 class="h6 mb-2">Select2 APIs</h2>
                        <p class="text-muted mb-0">Ensure endpoints return <code>results</code> and <code>pagination.more</code>.</p>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card title="Common Commands" subtitle="Run focused checks while coding, then broader checks before pushing.">
            <div class="row g-3">
                <div class="col-12 col-lg-6">
                    <h2 class="h6 mb-2">Focused feature tests</h2>
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>php artisan test --compact tests/Feature/UIKitTest.php
php artisan test --compact tests/Feature/CategoriesBulkActionTest.php
php artisan test --compact tests/Feature/DashboardTest.php</code></pre>
                </div>
                <div class="col-12 col-lg-6">
                    <h2 class="h6 mb-2">Frontend build</h2>
                    <pre class="bg-dark text-white rounded p-3 mb-0"><code>npm run build</code></pre>
                </div>
            </div>
        </x-card>

        <x-card title="Example Pest Test" subtitle="A local-only docs/page test.">
            <pre class="bg-dark text-white rounded p-3 mb-0"><code>it('renders developer docs locally', function () {
    $this-&gt;app-&gt;detectEnvironment(fn () =&gt; 'local');

    $user = User::factory()-&gt;create();

    $this-&gt;actingAs($user)
        -&gt;get(route('developer-docs'))
        -&gt;assertOk()
        -&gt;assertSee('Developer Docs');
});

it('hides developer docs outside local environments', function () {
    $this-&gt;app-&gt;detectEnvironment(fn () =&gt; 'production');

    $user = User::factory()-&gt;create();

    $this-&gt;actingAs($user)
        -&gt;get(route('developer-docs'))
        -&gt;assertNotFound();
});</code></pre>
        </x-card>

        <x-card title="Git Hygiene" subtitle="Keep commits reviewable.">
            <ul class="text-muted mb-0">
                <li>Do not commit <code>.DS_Store</code>, IDE files, or unrelated local changes.</li>
                <li>Keep commits scoped to one feature or cleanup theme.</li>
                <li>Summarize tests and build output when handing work back.</li>
            </ul>
        </x-card>
    </div>
@endsection
