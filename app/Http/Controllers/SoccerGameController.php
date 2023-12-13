<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SoccerGame;
use App\Models\SoccerGamePlayers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SoccerGameController extends Controller
{
    public function index()
    {
        return view('sorteio');
    }

    public function realizarSorteio(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nomeDoJogo' => 'required|string',
                'totalJogadoresTime' => 'required|integer',
                'dataDoJogo' => 'required|date'
            ]);

            $params = $request->all();
            $dataDoJogo = $params['dataDoJogo'];
            $dataDoJogoObj = Carbon::createFromFormat('Y-m-d', $dataDoJogo);

            if ($dataDoJogoObj->isPast()) {
                return redirect()->back()->with('error', 'Essa data já passou.');
            }

            // Verificar se o número total de confirmados é suficiente para o sorteio
            $numeroJogadoresPorTime = $params['totalJogadoresTime'];
            $nomeDoJogo = $params['nomeDoJogo'];
            $minimoConfirmados = $numeroJogadoresPorTime * 2;

            $totalConfirmados = Player::where('confirmado', true)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('soccergame_players')
                        ->whereRaw('soccergame_players.player_id = players.id');
                })
                ->count();

            if ($totalConfirmados < $minimoConfirmados) {
                return redirect()->back()->with('error', 'Número insuficiente de jogadores confirmados para o sorteio.');
            }

            // Verificar o número de goleiros confirmados
            $maximoGoleirosPorTime = 1;
            $goleirosConfirmados = Player::where('goleiro', true)->where('confirmado', true)->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('soccergame_players')
                    ->whereRaw('soccergame_players.player_id = players.id');
            })->count();

            if ($goleirosConfirmados < $maximoGoleirosPorTime) {
                return redirect()->back()->with('error', 'Número insuficiente de goleiros confirmados para o sorteio.');
            }

            if ($goleirosConfirmados > $maximoGoleirosPorTime * 2) {
                return redirect()->back()->with('error', 'Número de goleiros está acima do permitido, apenas um por time.');
            }

            // Obter jogadores confirmados
            $jogadoresConfirmados = Player::where('confirmado', true)->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('soccergame_players')
                    ->whereRaw('soccergame_players.player_id = players.id');
            })->get();


            // Ordenar jogadores por nível (opcional)
            $jogadoresConfirmados = $jogadoresConfirmados->sortByDesc('nivel');

            // Dividir jogadores em dois times
            $numeroTimes = 2;
            $jogadoresPorTime = floor($totalConfirmados / $numeroTimes);

            // Garantir que cada time tenha pelo menos $numeroJogadoresPorTime jogadores
            if ($jogadoresPorTime < $numeroJogadoresPorTime) {
                return redirect()->back()->with('error', 'Número insuficiente de jogadores confirmados para formar times.');
            }

            // Criar um novo registro na tabela soccergame
            $soccerGame = new SoccerGame([
                'nome' => 'Nome do Jogo',       // Substitua pelo nome desejado
                'data' => $dataDoJogo,        // Substitua pela data desejada
                'num_jogadores_por_time' => $numeroJogadoresPorTime,        // Substitua pelo valor desejado
            ]);

            $soccerGame->save();

            // Inicializar arrays para armazenar os jogadores em cada time
            $time1Players = [];
            $time2Players = [];

            $distribuirJogadores = 0;
            // Distribuir jogadores entre os times
            foreach ($jogadoresConfirmados as $key => $jogador) {

                if ($distribuirJogadores < $minimoConfirmados) {
                    $distribuirJogadores++;

                $time = ($key % $numeroTimes) + 1;
                // Alternar entre os times
                $playerData = [
                    'soccergame_id' => $soccerGame->id,
                    'player_id' => $jogador->id,
                    'team_id' => $time,
                    'created_at' => now(),
                ];

                if ($time === 1) {
                    $time1Players[] = $playerData;
                } else {
                    $time2Players[] = $playerData;
                }
                }
            }

            // Inserir jogadores na tabela soccergame_players
            SoccerGamePlayers::insert($time1Players);
            SoccerGamePlayers::insert($time2Players);

            return redirect()->back()->with('success', 'Sorteio realizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', 'Erro ao cadastrar sorteio!');
        }
    }

    public function destroy($id)
    {
        try {
            $soccerPlayers = SoccerGamePlayers::where('soccergame_id', $id)->delete();
            $soccer = SoccerGame::findOrFail($id);
            $soccer->delete();

            return redirect()->back()->with('success', 'Jogo cancelado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cancelar jogo.');
        }
    }

    public function show($id)
    {
        try {
            $soccerGame  = SoccerGame::findOrFail($id);
            $soccerGame->data = Carbon::parse($soccerGame->data);

            $players = DB::table('soccergame_players')
                ->join('players', 'soccergame_players.player_id', '=', 'players.id')
                ->select('players.*','soccergame_players.team_id')
                ->where('soccergame_players.soccergame_id', $soccerGame->id)
                ->orderBy('soccergame_players.team_id','asc')
                ->get();

            $teams = [];

            foreach($players as $player) {
                $teams[$player->team_id][] = $player;
            }

            return view('soccergame/show', ['soccerGame' => $soccerGame, 'teams' => $teams]);
        } catch (\Exception $e) {
            return redirect()->route('index')->with('error', 'Jogo não encontrado.');
        }
    }
}
