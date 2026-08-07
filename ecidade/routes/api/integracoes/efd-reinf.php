<?php
// php5.6 artisan route:list --path=v4/api/integracoes/efd-reinf/
Route::prefix('configuracao')
    ->middleware('legacySession')
    ->group(function () {
        Route::post('/get', 'ConfiguracaoController@getConfig');
        Route::post('/save', 'ConfiguracaoController@saveConfig');
});

Route::prefix('unidaderesponsavel')
    ->group(function () {
        Route::post('/get', 'UnidadeResponsavelController@get');
        Route::post('/save', 'UnidadeResponsavelController@save');
        Route::post('/delete', 'UnidadeResponsavelController@delete');
});
