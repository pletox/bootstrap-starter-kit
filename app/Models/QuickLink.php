<?php

namespace App\Models;

// STARTER-KIT-TENANCY:model-imports
use App\Models\Concerns\BelongsToTenant;
// END-STARTER-KIT-TENANCY:model-imports

use Database\Factories\QuickLinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickLink extends Model
{
    /** @use HasFactory<QuickLinkFactory> */
    use HasFactory;
// STARTER-KIT-TENANCY:model-trait
    use BelongsToTenant;
// END-STARTER-KIT-TENANCY:model-trait


    protected $guarded = ['id'];
}
