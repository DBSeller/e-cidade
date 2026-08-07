<?php
Route::prefix('relatorio')->group(function () {
    Route::post('transferencia-bens-aberto', 'TransferenciaBensAbertoController@relatorioTransferenciaBensAberto');
});

Route::prefix('etiquetas')->group(function () {
    Route::post('imprimir', 'EmitirEtiquetasController@emitirEtiquetas');
});

Route::prefix('consulta')->group(function () {
    Route::prefix('bem')->group(function () {
        Route::post('buscar', 'BensController@buscar');
    });
});

