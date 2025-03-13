<?php
// php5.6 artisan route:list --path=rest/v1/

$path = "\App\Domain\SIM\Controllers\\";
Route::prefix('/pesquisapessoa')->namespace('App\Domain\SIM\Controllers')->group(function () {
    Route::get('/txt_nome/{nome}/{tp_pesquisa?}', "PessoasController@pesquisaPorNome");
    Route::get('/nro_cpf/{cpf}', "PessoasController@pesquisaPorCpf");
    Route::get('/nro_rg/{rg}', "PessoasController@pesquisaPorRg");
    Route::get('/nro_rg/{rg}', "PessoasController@pesquisaPorRg");
});

Route::prefix('/enderecopessoa')->namespace('App\Domain\SIM\Controllers')->group(function () {
    Route::get('/{cgm}', "PessoasController@pesquisaEndereco");
});

