<?php

use App\Enums\StatusEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 300)->unique();
            $table->integer('email_verification_code')->nullable();
            $table->timestampTZ('email_verification_expires_at')->nullable();
            $table->integer('email_verification_attempts')->default(0);
            $table->timestampTZ('email_verified_at')->nullable();
            $table->string('phone', 50)->unique();
            $table->string('password', 255);
            $table->enum('access_status', StatusEnum::cases())->default(StatusEnum::ACCESS->value);
            $table->rememberToken();
            $table->timestampsTZ();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestampTZ('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
