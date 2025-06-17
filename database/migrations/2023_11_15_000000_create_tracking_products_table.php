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
        Schema::create('tracking_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('numberId')->unique();
            $table->string('category');
            $table->integer('quantity');
            $table->string('label');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status'); // to track the expiration date based on the duration to the expiration
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_products');
    }
};
