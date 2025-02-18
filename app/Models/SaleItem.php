<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $guarded = ['id'];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
