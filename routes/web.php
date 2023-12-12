<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoccerGameController;
use App\Http\Controllers\PlayerController;

Route::get('/', [\App\Http\Controllers\Controller::class, 'index'])->name('index');

Route::group(['prefix' => '/jogadores'], function () {
    // Rotas para /jogadores

    Route::get('/create', [PlayerController::class, 'create'])->name('jogadores.create');
    Route::get('/confirmar/{id}', [PlayerController::class, 'confirmaJogador'])->name('jogadores.confirmar');
    Route::get('/list', [PlayerController::class, 'index'])->name('jogadores.index');
    Route::post('/', [PlayerController::class, 'store'])->name('jogadores.store');
    Route::get('/view/{id}', [PlayerController::class, 'show'])->name('jogadores.show');
    Route::get('/edit/{id}', [PlayerController::class, 'edit'])->name('jogadores.edit');
    Route::put('/update/{id}', [PlayerController::class, 'update'])->name('jogadores.update');
    Route::delete('/delete/{id}', [PlayerController::class, 'destroy'])->name('jogadores.destroy');

    // Rota para realizar o sorteio
    Route::post('/realizar-sorteio', [SoccerGameController::class, 'realizarSorteio'])->name('jogadores.realizarSorteio');
});

Route::group(['prefix' => '/jogos'], function () {
    Route::get('/', [SoccerGameController::class, 'index'])->name('sorteio.index');
    Route::get('/view/{id}', [SoccerGameController::class, 'show'])->name('jogos.show');
    Route::delete('/delete/{id}', [SoccerGameController::class, 'destroy'])->name('jogos.destroy');
    Route::post('/realizar-sorteio', [SoccerGameController::class, 'realizarSorteio'])->name('sorteio.realizar');

});

?>
