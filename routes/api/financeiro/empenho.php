<?php
// php5.6 artisan route:list --path=v4/api/financeiro/empenho/

$path = "\App\Domain\Financeiro\Empenho\Controller\\";

Route::prefix('relatorio')->group(function () use ($path) {
    Route::post('retencoesEfdReinf', $path."RelatorioRetencoesEfdReinfController@emitirRelatorio");
});

Route::prefix('conferencia-extra-orcamentaria')->namespace('Empenho\Controller\\')->group(function () {
    Route::post('exportar', "ConferenciaExtraOrcamentariaController@exportar");
});
