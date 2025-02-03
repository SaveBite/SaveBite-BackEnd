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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained('users')->onDelete("cascade");
            $table->date('Date');
            $table->string('ProductName');
            $table->string('Category');
            $table->decimal('UnitPrice', 8, 2);
            $table->integer('StockQuantity');
            $table->integer('ReorderLevel');
            $table->integer('ReorderQuantity');
            $table->integer('UnitsSold');
            $table->decimal('SalesValue', 10, 2);
            $table->string('Month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
