<?php
// php5.6 artisan route:list --path=v4/api/financeiro/tesouraria
Route::prefix('')->namespace('Tesouraria\Controller\\')->group(function () {
    Route::post('importar', "ImportarArquivoTefController@store");
    Route::apiResource('processar', "ProcessarArquivoTefController");
    Route::post('inconsistente', "ProcessarArquivoTefController@registrarInconsistente");

    //
    Route::post('contas-pendentes', "ImplantacaoConciliacaoBancariaController@contasPendentes");
    Route::post('processar-implantacao', "ImplantacaoConciliacaoBancariaController@processarImplantacao");
    Route::post(
        'processar-implantacao-por-conta',
        "ImplantacaoConciliacaoBancariaController@processarImplantacao"
    );
});

Route::prefix('relatorio')->namespace('Tesouraria\Controller\\')->group(function () {
    Route::post('tef', "RelatorioArquivoTefController@tef");
    Route::post('ExtratoContaBancaria', 'RelatorioExtratoContaBancariaController@extratoContaBancaria');
});

Route::prefix('contatesouraria')->namespace('Tesouraria\Controller\\')->group(function () {
    Route::post('buscar', "ContaBancariaOutrosDadosController@buscar");
    Route::post('alterar', "ContaBancariaOutrosDadosController@alterar");
});
