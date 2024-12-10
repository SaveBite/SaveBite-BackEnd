<?php

use App\Http\Enums\UserType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->text('fcm_token')->nullable();
            $table->enum('type', UserType::values());
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->foreignId('login_answer_id')->nullable()->constrained('login_answers','id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
