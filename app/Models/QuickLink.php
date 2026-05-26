<?php

namespace App\Models;

use Database\Factories\QuickLinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickLink extends Model
{
    /** @use HasFactory<QuickLinkFactory> */
    use HasFactory;

    protected $guarded = ['id'];
}
