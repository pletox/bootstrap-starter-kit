<?php

use App\Console\Commands\ManageTenancy;
use Illuminate\Filesystem\Filesystem;

it('restores the registration return without joining the closing method brace', function () {
    $path = app_path('Actions/Fortify/CreateNewUser.php');
    $files = new Filesystem;
    $original = $files->get($path);

    $files->put($path, <<<'PHP'
<?php

class CreateNewUser
{
    public function create(array $input): mixed
    {
        // STARTER-KIT-TENANCY:registration-create
        return DB::transaction(function () use ($input): User {
            return $input;
        });
        // END-STARTER-KIT-TENANCY:registration-create
    }
}
PHP);

    try {
        $command = new ManageTenancy($files);
        $method = new ReflectionMethod($command, 'restoreRegistrationReturn');
        $method->invoke($command);

        $contents = $files->get($path);

        expect($contents)
            ->not->toContain(']);    }')
            ->toContain("        ]);\n    }");
    } finally {
        $files->put($path, $original);
    }
});

it('generates tenant migrations without forcing tenant id column placement', function () {
    $command = new ManageTenancy(new Filesystem);

    $createStubMethod = new ReflectionMethod($command, 'tenantMigrationCreateStub');
    $tenantColumnsMethod = new ReflectionMethod($command, 'tenantColumnsMigration');

    $createStub = $createStubMethod->invoke($command);
    $tenantColumnsMigration = $tenantColumnsMethod->invoke($command, ['categories']);

    expect($createStub)
        ->toContain("\$table->foreignId('tenant_id')->constrained()->cascadeOnDelete();")
        ->not->toContain("foreignId('tenant_id')->after('id')");

    expect($tenantColumnsMigration)
        ->toContain("\$table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();")
        ->not->toContain("foreignId('tenant_id')->nullable()->after('id')");
});
