<?php

// Jetom
Route::prefix('jetom')->namespace('Jetom')->group(function () {
    $pathJetom = "\App\Domain\RecursosHumanos\Pessoal\Controller\Jetom\\";
    $funcao = $pathJetom . "FuncaoController";
    $comissao = $pathJetom . "ComissaoController";
    $comissaoFuncao = $pathJetom . "ComissaoFuncaoController";
    $comissaoConfiguracao = $pathJetom . "ComissaoConfiguracaoController";
    $tipoSessao = $pathJetom . "TipoSessaoController";
    $comissaoServidor = $pathJetom . "ComissaoServidorController";
    $comissaoSessao = $pathJetom . "SessaoController";
    $comissaoTipoSessao = $pathJetom . "ComissaoTipoSessaoController";
    $permissaoComissao = $pathJetom . "PermissaoComissaoController";
    $importarArquivoPonto = $pathJetom . "ArquivoPontoController";

    Route::get('tiposessao', $tipoSessao . "@index");
    Route::get('tiposessao/all', $tipoSessao . "@all");

    Route::get('funcao', $funcao . "@index");
    Route::get('funcao/all', $funcao . "@all");
    Route::get('funcao/show', $funcao . "@show");
    Route::get('funcao/find', $funcao . "@find");
    Route::post('funcao/', $funcao . "@store");
    Route::post('funcao/alterar', $funcao . "@edit");
    Route::post('funcao/deletar', $funcao . "@delete");

    Route::get('comissao', $comissao . "@index");
    Route::get('comissao/show', $comissao . "@show");
    Route::get('comissao/getComissao', $comissao . "@getComissao");
    Route::post('comissao/save', $comissao . "@store");
    Route::post('comissao/update', $comissao . "@update");
    Route::post('comissao/delete', $comissao . "@delete");

    Route::get('comissao/funcao', $comissaoFuncao . "@index");
    Route::post('comissao/funcao', $comissaoFuncao . "@store");
    Route::get('comissao/funcao/show', $comissaoFuncao . "@show");
    Route::post('comissao/funcao/update', $comissaoFuncao . "@update");
    Route::post('comissao/funcao/delete', $comissaoFuncao . "@destroy");

    Route::get('comissao/config', $comissaoConfiguracao . "@index");
    Route::post('comissao/config', $comissaoConfiguracao . "@store");
    Route::get('comissao/config/show', $comissaoConfiguracao . "@show");
    Route::post('comissao/config/update', $comissaoConfiguracao . "@update");
    Route::post('comissao/config/delete', $comissaoConfiguracao . "@destroy");

    Route::get( 'comissao/servidor', $comissaoServidor . "@index");
    Route::post('comissao/servidor', $comissaoServidor . "@store");
    Route::get( 'comissao/servidor/show', $comissaoServidor . "@show");
    Route::post('comissao/servidor/update', $comissaoServidor . "@update");
    Route::post('comissao/servidor/delete', $comissaoServidor . "@destroy");

    Route::get( 'comissao/tiposessao', $comissaoTipoSessao . "@index");
    Route::post('comissao/tiposessao', $comissaoTipoSessao . "@store");
    Route::get( 'comissao/tiposessao/show', $comissaoTipoSessao . "@show");
    Route::post('comissao/tiposessao/update', $comissaoTipoSessao . "@update");
    Route::post('comissao/tiposessao/delete', $comissaoTipoSessao . "@destroy");

    Route::resource('comissao/permissao', $permissaoComissao, [
        'only' => [ 'store', 'index', 'show' ],
        'parameters' => [ 'permissao' => 'id' ]
    ]);

    Route::post('comissao/permissao/update', $permissaoComissao . "@update");
    Route::post('comissao/permissao/delete', $permissaoComissao . "@destroy");

    Route::post('sessao/processar', "{$comissaoSessao}@processar");
    Route::resource('sessao', $comissaoSessao, [
        'only' => [ 'store', 'index', 'show', 'destroy' ]
    ]);

    Route::post('importar/arquivoponto', "{$importarArquivoPonto}@importar");
    Route::resource('importar', $importarArquivoPonto, [
        'only' => [ 'store', 'index', 'show', 'destroy' ]
    ]);
});

Route::prefix('contra-cheques')->group(function () {
    $path = "\App\Domain\RecursosHumanos\Pessoal\Controller\\";
    $contraChequeController = "{$path}ContraChequesController";
    Route::post('processar-competencia', "{$contraChequeController}@processarEmissao");
});
