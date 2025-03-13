<?php

// php5.6 artisan route:list --path=v4/api/assinador
Route::prefix('')->namespace('Controllers\\')->group(function () {
    Route::get('obter-arquivo-base64/{eid}', "AssinadorAPI@obterArquivoBase64API");
    Route::post('salvar-arquivo-tmp', "AssinadorAPI@salvarArquivoTMPeRetornarArquivo");
    Route::post('obter-dados-arquivo', "AssinadorAPI@getDataFromFile");
    Route::post('obter-assinantes-portaria', "AssinadorAPI@getSignersFromID");
    Route::post('obter-configuracao', "AssinadorAPI@getSignerConfig");
    Route::post('recuperar-pfx', "AssinadorAPI@recuperarPFX");
});
