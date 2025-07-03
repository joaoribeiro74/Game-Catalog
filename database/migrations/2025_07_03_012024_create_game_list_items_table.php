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
        Schema::create('game_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_list_id')->constrained('game_lists')->onDelete('cascade');
            $table->unsignedBigInteger('game_id'); // appid da Steam
            $table->timestamp('added_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_list_items');
    }
};
