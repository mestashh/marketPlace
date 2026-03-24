<?php

use App\Enums\PayoutStatusEnum;
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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('seller_id')->constrained()->restrictOnDelete();
            $table->enum('status', PayoutStatusEnum::cases())->default(PayoutStatusEnum::PENDING->value);
            $table->integer('amount');
            $table->timestampsTZ();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
