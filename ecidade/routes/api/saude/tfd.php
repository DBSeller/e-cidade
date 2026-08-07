<?php
Route::prefix('relatorio')->group(function() {
    Route::post('viagens-por-motorista', 'AgendaSaidaController@relatorioViagensPorMotorista');
});
