<?php
// php5.6 artisan route:list --path=v4/api/configuracao/relatorios-legais
Route::prefix('')->namespace('Controller\\')->group(function () {
    Route::get('lrf', "RelatoriosLegaisController@relatoriosLRF");
    Route::get('periodos/{relatorio}', "RelatoriosLegaisController@periodos");
    Route::post('upload/template', "RelatoriosLegaisController@upload");
});
