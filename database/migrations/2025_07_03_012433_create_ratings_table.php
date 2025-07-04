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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('game_id');
            $table->decimal('rating', 2, 1); // 1 a 5, pode ser nulo se só curtiu
            $table->boolean('liked')->default(false);
            $table->text('review')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'game_id']); // pra não ter avaliações duplicadas do mesmo user no mesmo game
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
