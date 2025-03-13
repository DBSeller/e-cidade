<?php
$path = "\App\Domain\Tributario\Arrecadacao\Controllers\\";

Route::prefix("tef")->middleware(["api", "auth:api", "legacySession"])->group(function () use ($path) {
    Route::post("baixar-debito", "{$path}TEFController@baixaAutomaticaDebito");
    Route::post("incluir-operacao", "{$path}TEFController@incluirOperacao");
    Route::post("confirmar-operacao", "{$path}TEFController@confirmarOperacao");
    Route::post("desfazer-operacao", "{$path}TEFController@desfazerOperacao");
    Route::post("alterar-operacao", "{$path}TEFController@alterarOperacao");
});

Route::prefix("tef")->middleware(["api", "clientCredential", "legacySession"])->group(function () use ($path) {
    Route::post("comprovante-desfazimento", "{$path}TEFController@comprovanteDesfazimento");
    Route::post("relatorio-pendentes", "{$path}TEFController@relatorioPendentes");
    Route::post("operacoes-pendentes", "{$path}TEFController@operacoesPendentes");
});

Route::prefix("controle-parcelamentos-vencidos")->middleware(["api", "auth:api"])->group(function () {
    Route::prefix("agendamento")->group(function () {
        Route::get('', "AgendamentoControleParcelamentoController@getAll");
        Route::post("salvar", "AgendamentoControleParcelamentoController@salvar");
        Route::post("desativar", "AgendamentoControleParcelamentoController@desativar");
    });
    Route::get('acoes', 'AcaoControleParcelamentoController@getAll');
    Route::get('tipo-parcelamento/{id}', 'TipoParcelamentoControleParcelamentoController@getByRegra');
    Route::get('buscar/{numParcelamento}', 'ReversaoControleParcelamentoController@buscar');
    Route::post('processar/{numParcelamento}', 'ReversaoControleParcelamentoController@processar');
});

$pathControllers = '\App\Domain\Tributario\Arrecadacao\Controllers\\';

Route::prefix("tipo-debito")
->middleware(["api", "auth:api", "legacySession"])
->namespace("TipoDebito")
->group(function () use ($pathControllers)
{
    $tipoDebito = $pathControllers . "TipoDebito\TipoDebitoPixController";

    Route::post(
        "/salvar", 
        $tipoDebito . "@salvar"
    ); 

    Route::post(
        "/atualizar/{k00_tipo}", 
        $tipoDebito . "@atualizar"
    )->where('k00_tipo', '[0-9]+'); 

    Route::post(
        "/validar/{codtipopix?}", 
        $tipoDebito . "@validationData"
    )->where('codtipopix', '[0-9]+'); 

    Route::get(
        "/{k00_tipo}", 
        $tipoDebito . "@pegarDadosTipoDebitoPix"
    )->where('k00_tipo', '[0-9]+'); 
    
    Route::delete(
        "/excluir/{k00_tipo}", 
        $tipoDebito . "@deletar"
    )->where('k00_tipo', '[0-9]+');
    
});
$regraEmissao = $pathControllers . "RegraEmissao\RegraEmissaoController";

Route::prefix("regra-emissao")
    ->middleware(["api", "auth:api", "legacySession"])
    ->namespace("RegraEmissao")
    ->group(function () use ($pathControllers)
{
    $regraEmissao = $pathControllers . "RegraEmissao\RegraEmissaoController";

    Route::post(
        "/salvar", 
        $regraEmissao . "@salvar"
    );

    Route::post(
        "/atualizar/{k48_sequencial}", 
        $regraEmissao . "@atualizar"
    )->where('k48_sequencial', '[0-9]+'); 

    Route::delete(
        "/excluir/{k48_sequencial}", 
        $regraEmissao . "@excluir"
    )->where('k48_sequencial', '[0-9]+'); 

    Route::get(
        "/{k48_sequencial}", 
        $regraEmissao . "@pegarDadosRegraEmissao"
        )->where('k48_sequencial', '[0-9]+'); 
});

Route::prefix('pix')->middleware(["api", "auth:api", "legacySession"])->group(function () use($pathControllers) {
    Route::post('gerar', $pathControllers . "RecibobarpixController@gerarPix");
});