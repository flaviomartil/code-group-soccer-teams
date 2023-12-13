<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoccerGamePlayers extends Model
{
    protected $table = 'soccergame_players';
    protected $fillable = ['id', 'soccergame_id','player_id','team_id'];
}
