<?php

namespace App\Http\Controllers;

use App\Models\SoccerGame;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $jogosConfirmados = SoccerGame::all();
        foreach ($jogosConfirmados as $jogo) {
            $jogo->data = Carbon::parse($jogo->data);
        }
        return view('soccergame/index', compact('jogosConfirmados'));
    }

}
