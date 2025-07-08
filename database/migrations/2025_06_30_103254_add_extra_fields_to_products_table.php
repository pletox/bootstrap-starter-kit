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
        Schema::table('products', function (Blueprint $table) {
            $table->string('serial_number')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('status')->nullable();
            $table->string('location')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('warranty_expiry')->nullable();
            $table->string('photo_url')->nullable();
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('serial_number');
            $table->dropColumn('brand');
            $table->dropColumn('model');
            $table->dropColumn('status');
            $table->dropColumn('location');
            $table->dropColumn('purchase_date');
            $table->dropColumn('warranty_expiry');
            $table->dropColumn('photo_url');
            $table->dropColumn('notes');
        });
    }
};
