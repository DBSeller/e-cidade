<?php
// php5.6 artisan route:list --path=v4/api/financeiro/orcamento
Route::prefix('cadastro')->group(function () {

    Route::post('especificacao-recurso/salvar', "EspecificacaoRecursoController@salvar");
    Route::post('especificacao-recurso/excluir', "EspecificacaoRecursoController@excluir");

    // manutenção dos recursos antes de 2022
    Route::post('recurso/salvar', "RecursoAntes2022Controller@salvar")->middleware('legacySession');
    Route::post('recurso/excluir', "RecursoAntes2022Controller@excluir")->middleware('legacySession');

    // manutenção dos recursos a partir de 2022
    Route::post('recurso/salvar-atualizado', "RecursoController@salvar");
    Route::get('recurso/{id}/{exercicio}', "RecursoController@buscar");
    Route::get('recursos/inativar/{exercicio}', "RecursoController@recursosInativar");
    Route::post('recurso/inativar', "RecursoController@inativar");
    Route::post('recurso/excluir', "RecursoController@excluir");

    Route::get('complemento', "ComplementoController@get");
    Route::post('complemento/salvar', "ComplementoController@salvar")->middleware('legacySession');
    Route::post('complemento/excluir', "ComplementoController@excluir")->middleware('legacySession');
});

Route::get('recursos/{data?}', "RecursoAntes2022Controller@get");

Route::get('utiliza-decimal/{exercicio}', "ParametroController@utilizaDecimal");



Route::prefix('relatorios')->group(function () {
    Route::post('siconfi-recursos-2022', "RecursoController@listaSiconfi2022");
    Route::post('meta-arrecadacao', "RelatoriosCronogramaController@metaArrecadacao");
    Route::post('cotas-despesa', "RelatoriosCronogramaController@cotaDespesa");
    Route::post('meta-x-cotas', "RelatoriosCronogramaController@metaVersusCota");
});

Route::prefix('de-para-siconfi')->group(function () {
    Route::get('exportar/{exercicio}', "RecursoController@exportarPlanilhaSiconfi");
    Route::post('importar', "RecursoController@importarPlanilhaSiconfi");
});

Route::get('classificacao/com-siconfi', "ClassificacaoFonteRecursoController@comSiconfi");

Route::get('tipos-detalhamento', "RecursoController@tiposDetalhamento");

Route::prefix('acompanhamento')->group(function () {
    Route::get('cronograma/bases-calculo-despesa', 'AcompanhamentoDesembolsoDespesaController@baseCalculo');
    Route::get('cronograma/despesa/{exercicio}', 'AcompanhamentoDesembolsoDespesaController@buscar');
    Route::post('cronograma/despesa/salvar', 'AcompanhamentoDesembolsoDespesaController@salvarEstimativa');
    Route::post('cronograma/despesa/recalcular', 'AcompanhamentoDesembolsoDespesaController@recalcular');

    Route::get('cronograma/bases-calculo-receita', 'AcompanhamentoDesembolsoReceitaController@baseCalculo');
    Route::get('cronograma/receita/{exercicio}', 'AcompanhamentoDesembolsoReceitaController@buscar');
    Route::post('cronograma/receita/salvar', 'AcompanhamentoDesembolsoReceitaController@salvarEstimativa');
    Route::post('cronograma/receita/recalcular', 'AcompanhamentoDesembolsoReceitaController@recalcular');
});
