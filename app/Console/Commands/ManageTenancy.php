<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

#[Signature('starter-kit:tenancy {action=install : install or remove} {--tables=categories,quick_links : Comma-separated tables that should receive tenant_id columns} {--models=Category,QuickLink : Comma-separated App\\Models classes that should use the tenant scope} {--force : Overwrite generated files and skip confirmation prompts}')]
#[Description('Install or remove the starter kit tenant workspace scaffolding.')]
class ManageTenancy extends Command
{
    private const START = 'STARTER-KIT-TENANCY';

    private const END = 'END-STARTER-KIT-TENANCY';

    public function __construct(private readonly Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $action = Str::lower((string) $this->argument('action'));

        return match ($action) {
            'install', 'add' => $this->install(),
            'remove', 'uninstall', 'reverse' => $this->remove(),
            default => $this->invalidAction($action),
        };
    }

    private function install(): int
    {
        $this->components->info('Installing tenant workspace scaffolding.');

        $tables = $this->optionList('tables');
        $models = $this->optionList('models');

        $this->ensureDirectory(app_path('Models/Concerns'));
        $this->ensureDirectory(app_path('Support'));
        $this->ensureDirectory(resource_path('views/tenants'));
        $this->ensureDirectory(base_path('stubs'));

        $this->writeGeneratedFile(app_path('Models/Tenant.php'), $this->tenantModel());
        $this->writeGeneratedFile(app_path('Models/Concerns/BelongsToTenant.php'), $this->belongsToTenantTrait());
        $this->writeGeneratedFile(app_path('Support/Tenancy.php'), $this->tenancySupport());
        $this->writeGeneratedFile(app_path('Http/Controllers/TenantsController.php'), $this->tenantsController());
        $this->writeGeneratedFile(resource_path('views/tenants/_workspace_dropdown.blade.php'), $this->workspaceDropdownView());
        $this->writeGeneratedFile(resource_path('views/tenants/_form.blade.php'), $this->tenantFormView());
        $this->writeGeneratedFile(base_path('stubs/model.stub'), $this->tenantModelStub());
        $this->writeGeneratedFile(base_path('stubs/migration.create.stub'), $this->tenantMigrationCreateStub());
        $this->writeMigration('create_tenancy_tables', $this->tenancyMigration(), Carbon::now());
        $this->writeMigration('add_tenant_id_to_workspace_tables', $this->tenantColumnsMigration($tables), Carbon::now()->addSecond());

        $this->patchUserModel();
        $this->patchCreateNewUserAction();
        $this->patchRegisterView();
        $this->patchSidebar();
        $this->patchWebRoutes();

        foreach ($models as $model) {
            $this->patchTenantScopedModel($model);
        }

        $this->components->info('Tenancy scaffolding installed.');
        $this->line('Next: run php artisan migrate');

        return self::SUCCESS;
    }

    private function remove(): int
    {
        if (! $this->option('force')) {
            $this->warn('Before removing tenancy scaffolding, roll back the generated tenancy migrations or create a new migration that removes the tenancy columns/tables.');

            if (! $this->confirm('Have you already handled the migrated database changes?', false)) {
                $this->components->warn('Tenancy scaffolding was not removed.');

                return self::FAILURE;
            }

            if (! $this->confirm('Remove generated tenancy files and integration blocks?', false)) {
                return self::FAILURE;
            }
        }

        $this->components->info('Removing tenant workspace scaffolding.');

        $this->deleteIfExists(app_path('Models/Tenant.php'));
        $this->deleteIfExists(app_path('Models/Concerns/BelongsToTenant.php'));
        $this->deleteIfExists(app_path('Support/Tenancy.php'));
        $this->deleteIfExists(app_path('Http/Controllers/TenantsController.php'));
        $this->deleteIfExists(resource_path('views/tenants/_workspace_dropdown.blade.php'));
        $this->deleteIfExists(resource_path('views/tenants/_form.blade.php'));
        $this->deleteGeneratedStub(base_path('stubs/model.stub'), $this->tenantModelStub());
        $this->deleteGeneratedStub(base_path('stubs/migration.create.stub'), $this->tenantMigrationCreateStub());
        $this->deleteMigration('create_tenancy_tables');
        $this->deleteMigration('add_tenant_id_to_workspace_tables');

        $this->removeMarkedBlock(app_path('Models/User.php'), 'user-imports');
        $this->removeMarkedBlock(app_path('Models/User.php'), 'user-methods');
        $this->removeMarkedBlock(app_path('Actions/Fortify/CreateNewUser.php'), 'registration-imports');
        $this->restoreRegistrationReturn();
        $this->removeBladeBlock(resource_path('views/auth/register.blade.php'), 'register-workspace-field');
        $this->restoreSidebarLogo();
        $this->removeBladeBlock(resource_path('views/layouts/partials/_sidebar.blade.php'), 'sidebar-tenant-modal');
        $this->removeMarkedBlock(base_path('routes/web.php'), 'routes-imports');
        $this->removeMarkedBlock(base_path('routes/web.php'), 'routes');

        foreach ($this->optionList('models') as $model) {
            $path = app_path("Models/{$model}.php");

            $this->removeMarkedBlock($path, 'model-imports');
            $this->removeMarkedBlock($path, 'model-trait');
        }

        $this->components->info('Tenancy scaffolding removed.');

        return self::SUCCESS;
    }

    private function invalidAction(string $action): int
    {
        $this->components->error("Unsupported action [{$action}]. Use install or remove.");

        return self::FAILURE;
    }

    /**
     * @return array<int, string>
     */
    private function optionList(string $name): array
    {
        return collect(explode(',', (string) $this->option($name)))
            ->map(fn (string $value): string => trim($value))
            ->filter()
            ->values()
            ->all();
    }

    private function ensureDirectory(string $path): void
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true);
        }
    }

    private function writeGeneratedFile(string $path, string $contents): void
    {
        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->components->warn("Skipped existing file: {$path}");

            return;
        }

        $this->files->put($path, $contents);
        $this->components->task("Wrote {$path}");
    }

    private function writeMigration(string $name, string $contents, Carbon $timestamp): void
    {
        if ($this->migrationPath($name)) {
            $this->components->warn("Skipped existing migration: {$name}");

            return;
        }

        $path = database_path('migrations/'.$timestamp->format('Y_m_d_His')."_{$name}.php");

        $this->files->put($path, $contents);
        $this->components->task("Wrote {$path}");
    }

    private function deleteIfExists(string $path): void
    {
        if ($this->files->exists($path)) {
            $this->files->delete($path);
            $this->components->task("Deleted {$path}");
        }
    }

    private function deleteGeneratedStub(string $path, string $expectedContents): void
    {
        if (! $this->files->exists($path)) {
            return;
        }

        if (trim($this->files->get($path)) !== trim($expectedContents)) {
            $this->components->warn("Skipped custom stub: {$path}");

            return;
        }

        $this->files->delete($path);
        $this->components->task("Deleted {$path}");
    }

    private function deleteMigration(string $name): void
    {
        $path = $this->migrationPath($name);

        if ($path) {
            $this->files->delete($path);
            $this->components->task("Deleted {$path}");
        }
    }

    private function migrationPath(string $name): ?string
    {
        $matches = $this->files->glob(database_path("migrations/*_{$name}.php"));

        return $matches[0] ?? null;
    }

    private function patchUserModel(): void
    {
        $path = app_path('Models/User.php');
        $contents = $this->files->get($path);

        $contents = $this->insertAfter($contents, "use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;\n", $this->block('user-imports', <<<'PHP'
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
PHP));

        $contents = $this->insertBefore($contents, "    public function pushSubscriptions(): HasMany\n", $this->block('user-methods', <<<'PHP'
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function ownedTenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'owner_id');
    }

    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    public function switchTenant(Tenant $tenant): void
    {
        abort_unless($this->tenants()->whereKey($tenant->getKey())->exists(), 403);

        $this->forceFill(['current_tenant_id' => $tenant->getKey()])->save();
        $this->setRelation('currentTenant', $tenant);
    }
PHP));

        $this->files->put($path, $contents);
    }

    private function patchCreateNewUserAction(): void
    {
        $path = app_path('Actions/Fortify/CreateNewUser.php');
        $contents = $this->files->get($path);

        $contents = $this->insertAfter($contents, "use Illuminate\\Support\\Facades\\Hash;\n", $this->block('registration-imports', <<<'PHP'
use Illuminate\Support\Facades\DB;
PHP));

        $contents = str_replace(
            "            'password' => \$this->passwordRules(),\n",
            "            'password' => \$this->passwordRules(),\n            'workspace_name' => ['required', 'string', 'min:2', 'max:120'],\n",
            $contents,
        );

        $original = <<<'PHP'
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
PHP;

        $replacement = $this->block('registration-create', <<<'PHP'
        return DB::transaction(function () use ($input): User {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            $tenant = $user->tenants()->create([
                'name' => $input['workspace_name'],
                'owner_id' => $user->getKey(),
            ], [
                'role' => 'owner',
            ]);

            $user->forceFill(['current_tenant_id' => $tenant->getKey()])->save();

            return $user;
        });
PHP);

        if (! str_contains($contents, $replacement) && str_contains($contents, $original)) {
            $contents = str_replace($original, rtrim($replacement), $contents);
        }

        $this->files->put($path, $contents);
    }

    private function restoreRegistrationReturn(): void
    {
        $path = app_path('Actions/Fortify/CreateNewUser.php');

        if (! $this->files->exists($path)) {
            return;
        }

        $contents = $this->files->get($path);

        if (! str_contains($contents, $this->marker('registration-create', true))) {
            return;
        }

        $replacement = <<<'PHP'
            'password' => $this->passwordRules(),
PHP;

        $contents = str_replace(
            "            'password' => \$this->passwordRules(),\n            'workspace_name' => ['required', 'string', 'min:2', 'max:120'],\n",
            $replacement."\n",
            $contents,
        );

        $replacement = <<<'PHP'
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
PHP;

        $contents = preg_replace($this->blockPattern('registration-create'), rtrim($replacement)."\n", $contents) ?? $contents;
        $this->files->put($path, $contents);
    }

    private function patchRegisterView(): void
    {
        $path = resource_path('views/auth/register.blade.php');
        $contents = $this->files->get($path);

        $contents = $this->insertAfter($contents, "            <x-input placeholder=\"Full name\" label=\"Name\" name=\"name\" id=\"name\"/>\n", $this->bladeBlock('register-workspace-field', <<<'BLADE'
            <x-input placeholder="Acme Workspace" label="Workspace Name" name="workspace_name" id="workspace_name"/>
BLADE));

        $this->files->put($path, $contents);
    }

    private function patchSidebar(): void
    {
        $path = resource_path('views/layouts/partials/_sidebar.blade.php');
        $contents = $this->files->get($path);

        $original = <<<'BLADE'
        <div class="sidebar-logo text-truncate p-2 my-2 mx-3 ms-3">
            <a href="#" class="text-sm d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center overflow-hidden rounded w-8 h-8 bg-black">
                    <x-lucide-building class="w-5 h-5 text-white"/>
                </div>
                <span class="text-truncate">{{ env('APP_NAME') }}</span>
            </a>
        </div>
BLADE;

        $replacement = $this->bladeBlock('sidebar-workspace-dropdown', <<<'BLADE'
        @include('tenants._workspace_dropdown')
BLADE);

        if (! str_contains($contents, $this->bladeMarker('sidebar-workspace-dropdown', true)) && str_contains($contents, $original)) {
            $contents = str_replace($original, rtrim($replacement), $contents);
        }

        $contents = $this->insertAfter($contents, "<div class=\"sidebar-backdrop\" id=\"sidebarBackdrop\"></div>\n", $this->bladeBlock('sidebar-tenant-modal', <<<'BLADE'
@include('tenants._form')
BLADE));

        $this->files->put($path, $contents);
    }

    private function restoreSidebarLogo(): void
    {
        $path = resource_path('views/layouts/partials/_sidebar.blade.php');

        if (! $this->files->exists($path)) {
            return;
        }

        $contents = $this->files->get($path);

        if (! str_contains($contents, $this->bladeMarker('sidebar-workspace-dropdown', true))) {
            return;
        }

        $replacement = <<<'BLADE'
        <div class="sidebar-logo text-truncate p-2 my-2 mx-3 ms-3">
            <a href="#" class="text-sm d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center overflow-hidden rounded w-8 h-8 bg-black">
                    <x-lucide-building class="w-5 h-5 text-white"/>
                </div>
                <span class="text-truncate">{{ env('APP_NAME') }}</span>
            </a>
        </div>
BLADE;

        $contents = preg_replace($this->bladeBlockPattern('sidebar-workspace-dropdown'), $replacement, $contents) ?? $contents;
        $this->files->put($path, $contents);
    }

    private function patchWebRoutes(): void
    {
        $path = base_path('routes/web.php');
        $contents = $this->files->get($path);

        $contents = $this->insertAfter($contents, "use App\\Http\\Controllers\\QuickLinksController;\n", $this->block('routes-imports', <<<'PHP'
use App\Http\Controllers\TenantsController;
PHP));

        $contents = $this->insertAfter($contents, "    Route::post('/home/recent-categories', [HomeController::class, 'recentCategories'])->name('home.recent-categories');\n", $this->block('routes', <<<'PHP'
    Route::post('tenants', [TenantsController::class, 'store'])->name('tenants.store');
    Route::post('tenants/{tenant}/switch', [TenantsController::class, 'switch'])->name('tenants.switch');
PHP));

        $this->files->put($path, $contents);
    }

    private function patchTenantScopedModel(string $model): void
    {
        $path = app_path("Models/{$model}.php");

        if (! $this->files->exists($path)) {
            $this->components->warn("Skipped missing model: App\\Models\\{$model}");

            return;
        }

        $contents = $this->files->get($path);
        $contents = $this->insertAfter($contents, "namespace App\\Models;\n\n", $this->block('model-imports', <<<'PHP'
use App\Models\Concerns\BelongsToTenant;
PHP));

        if (str_contains($contents, '    use HasFactory;')) {
            $contents = $this->insertAfter($contents, "    use HasFactory;\n", $this->block('model-trait', <<<'PHP'
    use BelongsToTenant;
PHP));
        }

        $this->files->put($path, $contents);
    }

    private function insertAfter(string $contents, string $needle, string $insert): string
    {
        if (str_contains($contents, $insert)) {
            return $contents;
        }

        return str_replace($needle, $needle.$insert."\n", $contents);
    }

    private function insertBefore(string $contents, string $needle, string $insert): string
    {
        if (str_contains($contents, $insert)) {
            return $contents;
        }

        return str_replace($needle, $insert."\n".$needle, $contents);
    }

    private function removeMarkedBlock(string $path, string $name): void
    {
        if (! $this->files->exists($path)) {
            return;
        }

        $contents = $this->files->get($path);
        $contents = preg_replace($this->blockPattern($name), '', $contents) ?? $contents;
        $this->files->put($path, $contents);
    }

    private function removeBladeBlock(string $path, string $name): void
    {
        if (! $this->files->exists($path)) {
            return;
        }

        $contents = $this->files->get($path);
        $contents = preg_replace($this->bladeBlockPattern($name), '', $contents) ?? $contents;
        $this->files->put($path, $contents);
    }

    private function block(string $name, string $contents): string
    {
        return $this->marker($name, true)."\n".rtrim($contents)."\n".$this->marker($name, false)."\n";
    }

    private function marker(string $name, bool $start): string
    {
        return '// '.($start ? self::START : self::END).":{$name}";
    }

    private function blockPattern(string $name): string
    {
        return '/'.preg_quote($this->marker($name, true), '/').'.*?'.preg_quote($this->marker($name, false), '/').'\\n?/s';
    }

    private function bladeBlock(string $name, string $contents): string
    {
        return $this->bladeMarker($name, true)."\n".rtrim($contents)."\n".$this->bladeMarker($name, false)."\n";
    }

    private function bladeMarker(string $name, bool $start): string
    {
        return '{{-- '.($start ? self::START : self::END).":{$name} --}}";
    }

    private function bladeBlockPattern(string $name): string
    {
        return '/'.preg_quote($this->bladeMarker($name, true), '/').'.*?'.preg_quote($this->bladeMarker($name, false), '/').'\\n?/s';
    }

    private function tenantModel(): string
    {
        return <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tenant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }
}
PHP;
    }

    private function belongsToTenantTrait(): string
    {
        return <<<'PHP'
<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use App\Support\Tenancy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder): void {
            $tenantId = Tenancy::currentTenantId();

            if (! $tenantId) {
                $builder->whereRaw('1 = 0');

                return;
            }

            $builder->where($builder->getModel()->qualifyColumn('tenant_id'), $tenantId);
        });

        static::creating(function ($model): void {
            if (! $model->tenant_id) {
                $model->tenant_id = Tenancy::currentTenantId();
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
PHP;
    }

    private function tenancySupport(): string
    {
        return <<<'PHP'
<?php

namespace App\Support;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Tenancy
{
    public static function currentTenant(): ?Tenant
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return null;
        }

        $tenant = $user->currentTenant;

        if ($tenant && $user->tenants()->whereKey($tenant->getKey())->exists()) {
            return $tenant;
        }

        $tenant = $user->tenants()->oldest('tenants.id')->first();

        if ($tenant) {
            $user->forceFill(['current_tenant_id' => $tenant->getKey()])->save();
            $user->setRelation('currentTenant', $tenant);
        }

        return $tenant;
    }

    public static function currentTenantId(): ?int
    {
        return self::currentTenant()?->getKey();
    }
}
PHP;
    }

    private function tenantsController(): string
    {
        return <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TenantsController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
        ]);

        $tenant = $request->user()->tenants()->create([
            'name' => $validated['name'],
            'owner_id' => $request->user()->getKey(),
        ], [
            'role' => 'owner',
        ]);

        $request->user()->switchTenant($tenant);

        return $this->tenantResponse($request, 'Workspace created successfully.');
    }

    public function switch(Request $request, Tenant $tenant): JsonResponse|RedirectResponse
    {
        $request->user()->switchTenant($tenant);

        return $this->tenantResponse($request, 'Workspace switched successfully.');
    }

    private function tenantResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return back()->with('status', $message);
    }
}
PHP;
    }

    private function workspaceDropdownView(): string
    {
        return <<<'BLADE'
@php
    $currentTenant = \App\Support\Tenancy::currentTenant();
    $tenants = auth()->user()?->tenants()->orderBy('name')->get() ?? collect();
@endphp

<div class="sidebar-logo dropdown px-4">
    <button type="button"
            class="btn w-100 h-100 justify-content-between text-start px-0 py-0 shadow-none border-0 bg-transparent"
            data-bs-toggle="dropdown"
            data-bs-display="static"
            aria-expanded="false">
        <span class="d-flex align-items-center gap-3 min-w-0">
            <span class="d-flex align-items-center justify-content-center overflow-hidden rounded-2 w-8 h-8 bg-black flex-shrink-0">
                <x-lucide-building-2 class="w-5 h-5 text-white"/>
            </span>
            <span class="d-grid min-w-0">
                <span class="text-truncate fw-semibold">{{ $currentTenant?->name ?? 'Select workspace' }}</span>
                <span class="text-muted text-xs text-truncate">Workspace</span>
            </span>
        </span>
        <x-lucide-chevron-down class="w-4 h-4 text-muted flex-shrink-0"/>
    </button>

    <div class="dropdown-menu dropdown-menu-start dropdown-modern rounded-3 shadow-sm border p-2 mt-2" style="width: min(15rem, calc(100vw - 1.5rem));">
        <div class="px-2 pb-2 text-xs text-muted text-uppercase fw-semibold">Workspaces</div>

        @forelse($tenants as $tenant)
            <form method="POST" action="{{ route('tenants.switch', $tenant) }}">
                @csrf
                <button type="submit" class="dropdown-item rounded-2 d-flex align-items-center justify-content-between gap-2 px-2 py-2 {{ $currentTenant?->is($tenant) ? 'active bg-primary-subtle text-primary' : '' }}">
                    <span class="d-flex align-items-center gap-2 min-w-0">
                        <span class="d-flex align-items-center justify-content-center rounded-2 bg-body-secondary w-7 h-7 flex-shrink-0">
                            <x-lucide-building-2 class="w-4 h-4"/>
                        </span>
                        <span class="text-truncate fw-semibold">{{ $tenant->name }}</span>
                    </span>
                    @if($currentTenant?->is($tenant))
                        <x-lucide-check class="w-4 h-4 flex-shrink-0"/>
                    @endif
                </button>
            </form>
        @empty
            <div class="px-2 py-2 text-muted text-sm">No workspaces yet.</div>
        @endforelse

        <div class="dropdown-divider my-2"></div>

        <button type="button" class="dropdown-item rounded-2 d-flex align-items-center gap-2 px-2 py-2 fw-semibold text-primary" data-bs-toggle="modal" data-bs-target="#tenantCreateModal">
            <x-lucide-plus class="w-4 h-4"/>
            <span>Create workspace</span>
        </button>
    </div>
</div>
BLADE;
    }

    private function tenantFormView(): string
    {
        return <<<'BLADE'
<x-modal id="tenantCreateModal" title="Create Workspace">
    <x-form method="POST" action="{{ route('tenants.store') }}">
        <x-modal.body class="space-y-3">
            <x-input label="Workspace Name" name="name" id="tenant_name" placeholder="Acme Workspace"/>
        </x-modal.body>

        <x-modal.footer>
            <x-button color="light" data-bs-dismiss="modal">Cancel</x-button>
            <x-button type="submit" color="primary">Create</x-button>
        </x-modal.footer>
    </x-form>
</x-modal>
BLADE;
    }

    private function tenantModelStub(): string
    {
        return <<<'PHP'
<?php

namespace {{ namespace }};

use App\Models\Concerns\BelongsToTenant;
{{ factoryImport }}
use Illuminate\Database\Eloquent\Model;

class {{ class }} extends Model
{
    {{ factory }}
    use BelongsToTenant;

    protected $guarded = ['id'];
}
PHP;
    }

    private function tenantMigrationCreateStub(): string
    {
        return <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('{{ table }}', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{{ table }}');
    }
};
PHP;
    }

    private function tenancyMigration(): string
    {
        return <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('tenant_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('member');
            $table->timestamps();
            $table->unique(['tenant_id', 'user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_tenant_id');
        });

        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('tenants');
    }
};
PHP;
    }

    /**
     * @param  array<int, string>  $tables
     */
    private function tenantColumnsMigration(array $tables): string
    {
        $tableList = collect($tables)
            ->map(fn (string $table): string => "'{$table}'")
            ->implode(', ');

        return <<<PHP
<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    private array \$tables = [{$tableList}];

    public function up(): void
    {
        foreach (\$this->tables as \$tableName) {
            if (! Schema::hasTable(\$tableName) || Schema::hasColumn(\$tableName, 'tenant_id')) {
                continue;
            }

            Schema::table(\$tableName, function (Blueprint \$table) {
                \$table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (\$this->tables as \$tableName) {
            if (! Schema::hasTable(\$tableName) || ! Schema::hasColumn(\$tableName, 'tenant_id')) {
                continue;
            }

            Schema::table(\$tableName, function (Blueprint \$table) {
                \$table->dropConstrainedForeignId('tenant_id');
            });
        }
    }
};
PHP;
    }
}
