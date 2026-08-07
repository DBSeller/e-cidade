<?php
// php5.6 artisan route:list --path=v4/api/financeiro/planejamento
$path = "\App\Domain\Financeiro\Planejamento\Controllers\\";

Route::prefix('consulta')->group(function () use ($path) {
    Route::get('plano/{id}', $path . "PlanejamentoController@show");
    Route::get('planos/{tipo}/{status}', $path . "PlanejamentoController@index");
    Route::get('planos/{tipo}', $path . "PlanejamentoController@porTipo");
    Route::get('planos-em-desenvolvimento/{tipo?}', $path . "PlanejamentoController@planejamentoEmDesenvolvimento");

    Route::get('situacoes/movimentar/plano/{id}', $path . "PlanejamentoController@possiveisSituacoesAtualizar");
    Route::get('ods', $path . "OdsController@get");
});

Route::post('identidade-organizacional', $path . "PlanejamentoController@salvarIdentidadeOrganizacional");
Route::post('comissao', $path . "PlanejamentoController@salvarComissao");
Route::post('objetivo-estrategico', $path . "PlanejamentoController@salvarObjetivoEstrategico");
Route::post('objetivo-estrategico/excluir', $path . "PlanejamentoController@removerObjetivoEstrategico");
Route::post('ppa', $path . "PlanejamentoController@salvarPPA");
Route::post('ldo', $path . "PlanejamentoController@salvarLDO");
Route::post('loa', $path . "PlanejamentoController@salvarLOA");
Route::post('remove', $path . "PlanejamentoController@remove");
Route::post('criarVinculo', $path . "PlanejamentoController@criarVinculo");

Route::post('status-planejamento/situacao', $path . "StatusPlanejamentoController@store");

/**
 * @todo refatorar projecao/despesa/calcular
 */
Route::post('projecao-despesa/recalcular', $path . "ProjecaoDespesaController@calcular");
Route::post('projecao-despesa/projecao', $path . "ProjecaoDespesaController@projecao");
Route::post('projecao-despesa/salvar-projecao', $path . "ProjecaoDespesaController@salvarProjecao");

// projecao receita
Route::post('projecao/receita/recalcular', $path . "ProjecaoReceitaController@recalcular");
Route::post('projecao/receita/buscar', $path . "ProjecaoReceitaController@buscar");
Route::post('projecao/receita/atualizar/valor-exercicio', $path . "ProjecaoReceitaController@previsaoExercicio");
Route::post('projecao/receita/atualizar/valor-base', $path . "ProjecaoReceitaController@valorBase");
// manutenção da receita
Route::post('receita/previsao/buscar', $path . "EstimativaReceitaController@buscar");
Route::post('receita/previsao/salvar', $path . "EstimativaReceitaController@salvar");
Route::post('receita/previsao/remover', $path . "EstimativaReceitaController@remover");
Route::post('receita/previsao/removerNaturezas', $path . "EstimativaReceitaController@removerNaturezas");
Route::get('receita/previsao/{id}', $path . "EstimativaReceitaController@show");
// cronograma de desembolso da receita
Route::post('receita/cronograma/buscar', $path . "CronogramaDesembolsoReceitaController@buscar");
Route::post('receita/cronograma/salvar-metas', $path . "CronogramaDesembolsoReceitaController@salvarMetas");
Route::post('receita/cronograma/recalcular', $path . "CronogramaDesembolsoReceitaController@recalcular");


//fator de correcao
Route::post('fator-correcao/despesa', $path . "FatorCorrecaoController@salvarDespesa");
Route::post('fator-correcao/receita', $path . "FatorCorrecaoController@salvarReceita");
Route::post('fator-correcao/index', $path . "FatorCorrecaoController@index");


Route::post('area-resultado/salvar', $path . "AreaResultadoController@salvar");
Route::post('area-resultado/excluir', $path . "AreaResultadoController@delete");

Route::post('areas-resultado/filtros', $path . "AreaResultadoController@buscar");

Route::post('objetivos-estrategicos/filtros', $path . "ObjetivoEstrategicoController@buscar");

Route::get('origens', $path . "OrigemController@index");
Route::get('periodos', $path . "PeriodoController@index");

Route::post('programas-estrategico/remover', $path . "ProgramaEstrategioController@delete");
Route::post('programas-estrategico/salvar', $path . "ProgramaEstrategioController@salvar");
// busca o plano aplicando os filtros informados
Route::post('programas-estrategico/filtros', $path . "ProgramaEstrategioController@buscar");
Route::post('programas-estrategico/saldo/iniciativas', $path . "ProgramaEstrategioController@calculaSaldoIniciativa");
Route::get('programas-estrategico', $path . "ProgramaEstrategioController@index");
Route::get('programas-estrategico/{id}', $path . "ProgramaEstrategioController@show");

// rotas do orgao do programa estratético
Route::post('orgao-programa/filtros', $path . "OrgaoProgramaEstrategioController@buscar");
Route::post('orgao-programa/salvar', $path . "OrgaoProgramaEstrategioController@salvar");

// rotas do objetivo do programa estratético
Route::post('objetivo-programa/filtros', $path . "ObjetivoProgramaEstrategioController@buscar");
Route::post('objetivo-programa/salvar', $path . "ObjetivoProgramaEstrategioController@salvar");
Route::post('objetivo-programa/remover', $path . "ObjetivoProgramaEstrategioController@delete");
Route::post(
    'objetivo-programa/saldo/iniciativas',
    $path . "ObjetivoProgramaEstrategioController@calculaSaldoIniciativa"
);


// rotas das metas dos objetivos do programa estratético
Route::post('meta-objetivo/salvar', $path . "MetaObjetivoController@salvar");
Route::post('meta-objetivo/remover', $path . "MetaObjetivoController@delete");

// rotas das indicador do programa estratético
Route::post('indicador-programa/salvar', $path . "IndicadorProgramaEstrategicoController@salvar");
Route::post('indicador-programa/remover', $path . "IndicadorProgramaEstrategicoController@delete");

// rotas das iniciativas dos objetivos do programa estratético
Route::post('iniciativa/filtros', $path . "IniciativaController@buscar");
Route::post('iniciativa/salvar', $path . "IniciativaController@salvar");
Route::post('iniciativa/remover', $path . "IniciativaController@delete");
Route::get('iniciativa/{id}', $path . "IniciativaController@show");
Route::get('iniciativa/regionalizacoes/{id}', $path . "IniciativaController@getRegionalizacoes");


// rotas das metas da iniciativa
Route::post('metas-iniciativa/salvar', $path . "MetasIniciativaController@salvar");

// rotas das regionalizações
Route::post('regionalizacao/salvar', $path . "IniciativaController@salvarRegionalizacoes");
Route::post('regionalizacao/excluir', $path . "IniciativaController@excluirRegionalizacoes");

// rotas das regionalizações
Route::post('abrangencia/salvar', $path . "IniciativaController@salvarAbrangencias");
Route::post('abrangencia/excluir', $path . "IniciativaController@excluirAbrangencias");

/**
 * Rotas de vínculos dos programas
 */
Route::post('programas-vincular-area/buscar', $path . "ProgramaPorAreaController@buscar");
Route::post('programas-vincular-area/vincular', $path . "ProgramaPorAreaController@vincular");

Route::post('programas-vincular-objetivo/buscar', $path . "ProgramaPorObjetivoController@buscar");
Route::post('programas-vincular-objetivo/vincular', $path . "ProgramaPorObjetivoController@vincular");

/**
 * rotas para manutenção dos vínculos das iniciativas com os objetivos dos programas estratégicos
 */
Route::post('iniciativa-vincular-objetivo/buscar', $path . "IniciativaPorObjetivoController@buscar");
Route::post('iniciativa-vincular-objetivo/vincular', $path . "IniciativaPorObjetivoController@vincular");

/**
 * Detalhamento da despesa
 */
Route::get('despesa/detalhamento/{id}', $path . "DetalhamentoDespesaController@show");
Route::post('despesa/detalhamento/buscar', $path . "DetalhamentoDespesaController@buscar");
Route::post('despesa/detalhamento/salvar', $path . "DetalhamentoDespesaController@salvar");
Route::post('despesa/detalhamento/remover', $path . "DetalhamentoDespesaController@delete");

Route::post('despesa/cronograma/buscar', $path . "CronogramaDesembolsoDespesaController@buscar");
Route::post('despesa/cronograma/salvar', $path . "CronogramaDesembolsoDespesaController@salvar");
Route::post('despesa/cronograma/recalcular', $path . "CronogramaDesembolsoDespesaController@recalcular");
Route::post(
    'despesa/cronograma/recalcularGeral',
    $path . "CronogramaDesembolsoDespesaController@recalcularGeral"
);

Route::prefix('relatorios')->group(function () use ($path) {
    Route::post('programa-estrategico', $path . "RelatorioProgramaTematicoController@emitir");
    Route::post('programa-gestao', $path . "RelatorioProgramaGestaoController@emitir");
    Route::post('por-elemento', $path . "RelatorioProjecaoPorElementoController@emitir");
    Route::post('projecao-receita', $path . "RelatorioProjecaoReceitaController@emitir");
    Route::post('resumo-projecao-receita', $path . "RelatorioProjecaoReceitaController@emitirResumo");
    Route::post('anexo-um', $path . "AnexosLdoController@anexoUm");
    Route::post('anexo-dois', $path . "AnexosLdoController@anexoDois");
    Route::post('anexo-tres', $path . "AnexosLdoController@anexoTres");
    Route::post('anexo-quatro', $path . "AnexosLdoController@anexoQuatro");
    Route::post('anexo-cinco', $path . "AnexosLdoController@anexoCinco");
    Route::post('anexo-seis', $path . "AnexosLdoController@anexoSeis");
    Route::post('anexo-sete', $path . "AnexosLdoController@anexoSete");
    Route::post('anexo-oito', $path . "AnexosLdoController@anexoOito");
    Route::post('projecao-despesa-agrupado', $path . "RelatorioProjecaoDespesaController@agrupadoPor");
    Route::post(
        'projecao-despesa-agrupado-sintetico',
        $path . "RelatorioProjecaoDespesaController@agrupadoSintetico"
    );
    Route::post('meta-arrecadacao', $path . "RelatoriosCronogramaController@metaArrecadacao");
    Route::post('cotas-despesa', $path . "RelatoriosCronogramaController@cotaDespesa");
    Route::post('meta-x-cotas', $path . "RelatoriosCronogramaController@metaVersusCota");
    Route::post('previsao-rcl-outros-anexos', $path . "RelatoriosPlanejamentoRclController@previsaoRclOutrosAnexos");

    // relatorios para auxiliar a conferencia dos recursos
    Route::post('projecao-receita-recurso', $path . "RelatorioProjecaoReceitaController@emitirConferenciaRecurso");
    Route::post(
        'projecao-despesa-conferencia-recurso',
        $path . "RelatorioProjecaoDespesaController@conferenciaRecurso"
    );
});

Route::get('configuracao', $path . "ConfiguracaoController@index");
Route::post('configuracao/salvar', $path . "ConfiguracaoController@salvar");

//
Route::get('pib/{planejamento_id}', $path . "PibController@show");
Route::post('pib', $path . "PibController@store");

Route::post('recalcular-valores-sinteticos', $path . "CalcularValoresSinteticosController@calcular");

Route::post('orcamento/gerar', $path . "GerarOrcamentoController@exportar");
Route::post('orcamento/cancelar', $path . "GerarOrcamentoController@cancelar");
