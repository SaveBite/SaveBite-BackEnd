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
        Schema::table('tracking_products', function (Blueprint $table) {
            $table->date('start_date')->nullable()->change();
            $table->string('image')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracking_products', function (Blueprint $table) {
            $table->date('start_date')->nullable(false)->change();
            $table->dropColumn('image'); // Dropping the image column
        });
    }
};