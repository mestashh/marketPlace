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
        Schema::create('seller_payout_methods', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payout_method_id')->constrained()->cascadeOnDelete();
            $table->enum('status', PayoutStatusEnum::cases())->default(PayoutStatusEnum::PENDING->value);
            $table->json('details');
            $table->unique(['seller_id', 'payout_method_id']);
            $table->timestampsTZ();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_payout_methods');
    }
};
