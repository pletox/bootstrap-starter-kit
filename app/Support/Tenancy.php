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