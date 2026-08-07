<?php
//php5.6 artisan route:list --path=v4/api/educacao/central-de-matriculas
$path = "\App\Domain\Educacao\CentralMatriculas\Controllers\\";

Route::post("escolas-disponiveis", "{$path}EscolasController@disponiveisPreMatricula");

Route::middleware(['api', 'clientCredential'])->group(function () use ($path) {
    Route::post("inscricao", "{$path}InscricaoController@inscricao");
    Route::post("emissao-protocolo", "{$path}InscricaoController@emissaoProtocolo");
});
