<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $nivel = rand(1, 5);
            $goleiro = ($i <= 2) ? true : false;

            Player::create([
                'nome' => 'Jogador ' . $i,
                'nivel' => $nivel,
                'goleiro' => $goleiro,
                'confirmado' => true,
            ]);
        }
    }
}
