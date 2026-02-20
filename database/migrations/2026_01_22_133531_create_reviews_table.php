<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('rating');
            $table->text('text');
            $table->unique(['user_id', 'product_id']);
            $table->timestamps();
        });
        DB::statement('
    ALTER TABLE reviews
    ADD CONSTRAINT reviews_rating_check
    CHECK (rating BETWEEN 1 AND 5)
');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('
            ALTER TABLE reviews
            DROP CONSTRAINT IF EXISTS reviews_rating_check
        ');
        Schema::dropIfExists('reviews');
    }
};
