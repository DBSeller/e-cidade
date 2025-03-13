<?php
// php5.6 artisan route:list --path=v4/api/patrimonial/material

Route::middleware("auth:api")->prefix('relatorios')->group(function () {
    Route::post("controle-estoque", "RelatoriosController@controleEstoque");
    Route::post('rastreabilidade', 'RelatoriosController@rastreabilidadeMaterial');
    Route::post("resumo-contabil-estoque", "RelatoriosController@resumoContabilEstoque");
});
