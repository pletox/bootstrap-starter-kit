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