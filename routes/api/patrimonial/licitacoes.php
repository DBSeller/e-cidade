<?php
// php5.6 artisan route:list --path=v4/api/patrimonial/licitacoes
Route::get('registrosDePreco', "ItensBloqueadosController@buscarRegistrosDePreco");
Route::post('itensBloqueados', "ItensBloqueadosController@emitir");
Route::post('tramita/importar', "TramitaController@importar");
Route::post('integracaocomprasbr/importar', "IntegracaoComprasBrController@import");
Route::post('integracaocomprasbr/exportar', "IntegracaoComprasBrController@export");
