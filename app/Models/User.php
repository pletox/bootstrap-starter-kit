<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// STARTER-KIT-TENANCY:user-imports
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// END-STARTER-KIT-TENANCY:user-imports

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['initials'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

// STARTER-KIT-TENANCY:user-methods
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
// END-STARTER-KIT-TENANCY:user-methods

    public function pushSubscriptions(): HasMany
    {
        return $this->hasMany(PushSubscription::class);
    }

    public function getInitialsAttribute()
    {
        $name = $this->name ?? '';
        $words = preg_split('/\s+/', trim($name));

        if (count($words) >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1).mb_substr($words[1], 0, 1));
        }

        return strtoupper(mb_substr($name, 0, 2));
    }
}
