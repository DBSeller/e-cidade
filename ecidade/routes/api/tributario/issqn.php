<?php
Route::middleware(['api', 'auth:api'])->prefix('alvaraeventos')->namespace('AlvaraEventos')->group(function () {

    $routePrefix = 'ordemservico';
    $path = "\App\Domain\Tributario\ISSQN\Controller\AlvaraEventos\\";
    $controller = $path . "OrdemServicoController";

    Route::get($routePrefix, $controller . "@index");
    Route::post($routePrefix, $controller . "@store");
    Route::get($routePrefix.'/show', $controller . "@show");
    Route::post($routePrefix.'/update', $controller . "@update");
    Route::post($routePrefix.'/delete', $controller . "@destroy");
    Route::post($routePrefix.'/processar', $controller . "@processar");
    Route::post($routePrefix.'/desprocessar', $controller . "@desprocessar");
    Route::get($routePrefix.'/getOrdemServico', $controller . "@getOrdemServico");

    $routePrefix = 'alvaraevento';
    $path = "\App\Domain\Tributario\ISSQN\Controller\AlvaraEventos\\";
    $controller = $path . "AlvaraEventoController";

    Route::get($routePrefix, $controller . "@index");
    Route::post($routePrefix, $controller . "@store");
    Route::get($routePrefix.'/show', $controller . "@show");
    Route::post($routePrefix.'/update', $controller . "@update");
    Route::post($routePrefix.'/delete', $controller . "@destroy");
    Route::get($routePrefix.'/getAlvaraEvento', $controller . "@getAlvaraEvento");

    $routePrefix = 'mensagempadrao';
    $path = "\App\Domain\Tributario\ISSQN\Controller\AlvaraEventos\\";
    $controller = $path . "MensagemPadraoController";

    Route::get($routePrefix, $controller . "@index");

});

Route::middleware(['api', 'auth:api'])->prefix('veiculos')->namespace('Veiculos')->group(function () {

    $routePrefix = 'veiculo';
    $path = "\App\Domain\Tributario\ISSQN\Controller\Veiculos\\";
    $controller = $path . "VeiculoController";

    Route::get($routePrefix, $controller . "@index");
    Route::post($routePrefix, $controller . "@store");
    Route::get($routePrefix.'/show', $controller . "@show");
    Route::post($routePrefix.'/update', $controller . "@update");
    Route::post($routePrefix.'/delete', $controller . "@destroy");
    Route::get($routePrefix.'/getVeiculo', $controller . "@getVeiculo");
    Route::post($routePrefix.'/desprocessar', $controller . "@desprocessar");


    $routePrefix = 'condutorauxiliar';
    $controller = $path . "CondutorAuxiliarController";

    Route::get($routePrefix, $controller . "@index");
    Route::post($routePrefix, $controller . "@store");
    Route::get($routePrefix.'/show', $controller . "@show");
    Route::post($routePrefix.'/update', $controller . "@update");
    Route::post($routePrefix.'/delete', $controller . "@destroy");

});

Route::middleware(['api'])->prefix('redesim')->group(function () {
    Route::middleware(['AuthRedesim'])->post("inclusao-inscricao", "RedesimController@incluirInscricao");
    Route::middleware(['auth:api'])->post("relatorio-inscricoes", "RedesimController@relatorioInscricoes");
});
