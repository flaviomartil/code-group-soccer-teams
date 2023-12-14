<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    public function index()
    {
        $jogadoresConfirmados = Player::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('soccergame_players')
                ->whereRaw('soccergame_players.player_id = players.id');
        })->get();
        return view('player/index', compact('jogadoresConfirmados'));
    }

    public function edit($id)
    {
        try {
            $player = Player::findOrFail($id);
            return view('player/update', compact('player'));
        } catch (\Exception $e) {
            // Trate a exceção conforme necessário, por exemplo, redirecione para uma página de erro.
            return redirect()->route('jogadores.index')->with('error', 'Jogador não encontrado.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|string',
                'nivel' => 'required|integer|between:1,5',
                'goleiro' => 'required|boolean',
                'confirmado' => 'required|boolean'
            ]);

            $player = Player::create($request->all());
            return redirect()->route('jogadores.index')->with('success', 'Jogador cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', 'Erro ao cadastrar jogador!');
        }
    }

    public function show($id)
    {
        try {
            $player = Player::findOrFail($id);
            return view('player/show', compact('player'));
        } catch (\Exception $e) {
            return redirect()->route('jogadores.index')->with('error', 'Jogador não encontrado.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'nivel' => 'required|integer|between:1,5',
                'goleiro' => 'required|boolean',
                'confirmado' => 'required|boolean',
            ]);

            $player = Player::findOrFail($id);
            $player->update($request->all());
            return redirect()->route('jogadores.index')->with('success', 'Jogador atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('jogadores.index')->with('error', 'Erro ao atualizar jogador.');
        }
    }

    public function destroy($id)
    {
        try {
            $player = Player::findOrFail($id);
            $player->delete();
            return redirect()->route('jogadores.index')->with('success', 'Jogador excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('jogadores.index')->with('error', 'Erro ao excluir jogador.');
        }
    }

    public function create() {
        return view('player/create');
    }

    public function confirmaJogador($id)
    {
        try {
            $player = Player::findOrFail($id);
            $player->confirmado = true;
            $player->save();
            return redirect()->route('jogadores.index')->with('success', 'Jogador confirmado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('jogadores.index')->with('error', 'Erro ao confirmar jogador.');
        }
    }
}
