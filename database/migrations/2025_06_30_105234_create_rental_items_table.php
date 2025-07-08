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
        Schema::create('rental_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id');
            $table->foreignId('product_id');
            $table->string('rate_type')->nullable();//Hourly/Daily/Weekly/Monthly/Custom
            $table->decimal('rate')->default(0);
            $table->decimal('deposit_amount')->default(0);
            $table->decimal('total_days')->default(0);
            $table->decimal('total_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_items');
    }
};
