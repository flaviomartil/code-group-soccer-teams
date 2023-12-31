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
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'nomeDoJogo' => 'required|string',
                'totalJogadoresTime' => 'required|integer',
                'dataDoJogo' => 'required|date'
            ]);

            $params = $request->all();
            $dataDoJogo = $params['dataDoJogo'];
            $nomeDoJogo = $params['nomeDoJogo'];
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

            if ($goleirosConfirmados < $maximoGoleirosPorTime * 2) {
                return redirect()->back()->with('error', 'Número insuficiente de goleiros confirmados para o sorteio.');
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

            // Criar um novo registro na tabela soccergame
            $soccerGame = new SoccerGame([
                'nome' => $nomeDoJogo,
                'data' => $dataDoJogo,
                'num_jogadores_por_time' => $numeroJogadoresPorTime,
            ]);

            $soccerGame->save();

            // Inicializar arrays para armazenar os jogadores em cada time
            $time1Players = [];
            $time2Players = [];

            $goleiros = $jogadoresConfirmados->where('goleiro', true)->where('confirmado', true);

            $time1Players[] = [
                'soccergame_id' => $soccerGame->id,
                'player_id' => $goleiros->shift()->id,
                'team_id' => 1,
                'created_at' => now(),
            ];

            // Adicionar goleiro ao segundo time
            $time2Players[] = [
                'soccergame_id' => $soccerGame->id,
                'player_id' => $goleiros->shift()->id,
                'team_id' => 2,
                'created_at' => now(),
            ];

            $goleirosId = array_merge(
                array_column($time1Players, 'player_id'),
                array_column($time2Players, 'player_id')
            );

            $distribuirJogadores = 1;
            // Distribuir jogadores entre os times
            foreach ($jogadoresConfirmados as $key => $jogador) {
               if ($jogador->goleiro) {
                   continue;
               }

                if ($distribuirJogadores < $minimoConfirmados) {

                $time = ($key % $numeroTimes) + 1;
                // Alternar entre os times

                if (!in_array($jogador->id,$goleirosId)) {
                    $goleirosId[] = $jogador->id;
                }    else {
                    continue;
                }

                    $distribuirJogadores++;

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

            // Garantir que cada time tenha pelo menos $numeroJogadoresPorTime jogadores
            if ($jogadoresPorTime > count($time1Players) || $jogadoresPorTime > count($time2Players)) {
                throw new \Exception('Número insuficiente de jogadores confirmados para formar times.');
            }

            // Inserir jogadores na tabela soccergame_players
            SoccerGamePlayers::insert($time1Players);
            SoccerGamePlayers::insert($time2Players);
            DB::commit();
            return redirect()->back()->with('success', 'Sorteio realizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            $msg = !empty($e->getMessage()) ? $e->getMessage() : 'Erro ao cadastrar sorteio';
            return redirect()->back()->withErrors($validator)->with('error', $msg);
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
