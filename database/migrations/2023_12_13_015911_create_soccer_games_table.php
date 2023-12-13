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
        Schema::create('soccergame', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data');
            $table->integer('num_jogadores_por_time');
            $table->timestamps();
        });

        Schema::create('soccergame_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soccergame_id')->constrained('soccergame');
            $table->foreignId('player_id')->constrained('players');
            $table->integer('team_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soccergame_players');
        Schema::dropIfExists('soccergame');
    }
};
