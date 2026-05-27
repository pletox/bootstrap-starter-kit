<?php

namespace App\Models;

// STARTER-KIT-TENANCY:model-imports
use App\Models\Concerns\BelongsToTenant;
// END-STARTER-KIT-TENANCY:model-imports

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
// STARTER-KIT-TENANCY:model-trait
    use BelongsToTenant;
// END-STARTER-KIT-TENANCY:model-trait


    protected $guarded = ['id'];
}
