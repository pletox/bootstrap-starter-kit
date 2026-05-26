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
        if (! Schema::hasColumn('categories', 'active')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->tinyInteger('active')->default(1)->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('categories', 'active')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }
    }
};
