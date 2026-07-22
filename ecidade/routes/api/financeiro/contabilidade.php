<?php
// php5.6 artisan route:list --path=v4/api/financeiro/contabilidade
Route::prefix('procedimento')->group(function () {

    Route::post(
        'manutencao/fonte-recurso/despesa/lancamentos',
        "ManutencaoFonteRecursoController@lancamentosDespesa"
    );
    Route::post(
        'manutencao/fonte-recurso/receita/lancamentos',
        "ManutencaoFonteRecursoController@lancamentosReceita"
    );
    Route::post(
        'manutencao/fonte-recurso/atualizarComplemento',
        "ManutencaoFonteRecursoController@atualizarComplemento"
    );
    Route::get(
        'encerramento-periodo-contabil/{instituicao}',
        "EncerramentoPeriodoContabilController@ultimaData"
    );
});

Route::prefix('relatorio')->group(function () {
    Route::post(
        'notificacao-recebimento-recursos-federais',
        "NotificacaoRecebimentoRecursosFederaisController@processar"
    );
    Route::post('balancete-receita-por-complemento', 'BalanceteReceitaController@emitirPorComplemento');
    Route::post('balancete-despena-por-complemento', 'BalanceteDespesaController@emitirPorComplemento');
    Route::post('demonstrativo-evolucao-receita', 'EvolucaoReceitaController@demonstrativoEvolucaoReceita');
    Route::post('demonstrativo-evolucao-despesa', 'EvolucaoDespesaController@demonstrativoEvolucaoDespesa');
});

Route::prefix('consulta')->group(function () {
    Route::post(
        'lancamento/conta-pcasp/documentos',
        "ConsultaLancamentoPcaspController@getValoresPorDocumento"
    );
    Route::post(
        'lancamento/conta-pcasp/recursos',
        "ConsultaLancamentoPcaspController@getValoresPorRecurso"
    );
    Route::post(
        'lancamento/conta-pcasp/info',
        "ConsultaLancamentoPcaspController@getInfoLancamentos"
    );
});

Route::prefix('')->group(function () {
    $path = "\App\Domain\Financeiro\Contabilidade\Controllers\\";
    Route::post(
        'relatorio-disponibilidade-recurso',
        $path . "DisponibilidadeRecursoController@processarSaldoDisponibilidadeRecurso"
    );
    Route::post(
        'relatorio-conferencia-por-recurso',
        $path . "DisponibilidadeRecursoController@relatorioConferenciaPorRecurso"
    );
    Route::post(
        'obter-dados-conferencia-por-recurso',
        $path . "DisponibilidadeRecursoController@obterDadosConferenciaPorRecurso"
    );
});

Route::prefix('relatorio/rreo')->group(function () {
    Route::post('anexo-1', "RREOAnexosController@anexoUm");
    Route::post('anexo-3-in-rs', "RREOAnexosController@anexoTresInRs");
    Route::post('anexo-3-mdf', "RREOAnexosController@anexoTresMdf");
    Route::post('anexo-4', "RREOAnexosController@anexoQuatro");
    Route::post('anexo-6', "RREOAnexosController@anexoSeis");
    Route::post('anexo-8', "RREOAnexosController@anexoOito");
});

Route::prefix('relatorio/rgf')->group(function () {
    Route::post('anexo-1-in-rs', "RGFAnexosController@anexoUmInRs");
    Route::post('anexo-1-mdf', "RGFAnexosController@anexoUmMdf");
    Route::post('anexo-2', "RGFAnexosController@anexoDois");
    Route::post('anexo-5', "RGFAnexosController@anexoCinco");
});

Route::prefix('plano-contas')->group(function () {
    Route::prefix('importar')->group(function () {
        Route::post('pcasp', "ImportarPlanoContasController@pcasp");
        Route::post('orcamentario/despesa', "ImportarPlanoContasController@despesa");
        Route::post('orcamentario/receita', "ImportarPlanoContasController@receita");
    });

    Route::prefix('emitir')->group(function () {
        Route::get('pcasp/{tipo}/{exercicio}', "EmissaoPlanoContasController@pcasp");
        Route::post('pcasp/mapeamento', "EmissaoPlanoContasController@mapeamento");
        Route::get('orcamentario/{tipoPlano}/{origem}/{exercicio}', "EmissaoPlanoContasController@orcamentario");
        Route::post('orcamentario/receita/mapeamento', "PlanoOrcamentarioReceitaController@mapeamento");
        Route::post('orcamentario/despesa/mapeamento', "PlanoOrcamentarioDespesaController@mapeamento");
    });

    Route::prefix('consulta')->group(function () {
        Route::post('pcasp/padrao', "PcaspController@getContasPadrao");
        Route::post('pcasp/ecidade', "PcaspController@getContasEcidade");

        // despesa
        Route::post('orcamentario/despesa/padrao', "PlanoOrcamentarioDespesaController@getContasPadrao");
        Route::post('orcamentario/despesa/ecidade', "PlanoOrcamentarioDespesaController@getContasMapearEcidade");

        //receita
        Route::post('orcamentario/receita/padrao', "PlanoOrcamentarioReceitaController@getContasPadrao");
        Route::post('orcamentario/receita/ecidade', "PlanoOrcamentarioReceitaController@getContasEcidade");
    });

    Route::post('pcasp/salvar-conta-caixa', 'PcaspController@salvarContaCaixa');
    Route::post('pcasp/salvar-conta-bancaria', 'PcaspController@salvarContaBancaria');
    Route::post('pcasp/salvar-conta-extra', 'PcaspController@salvarContaExtra');
    Route::post('pcasp/salvar-outras-contas', "PcaspController@salvarOutrasContas");
    Route::post('pcasp/remover-reduzido', "PcaspController@removerReduzido");

    Route::post('pcasp/vincular', "PcaspController@vincular");
    Route::post('pcasp/vincular-geral', "PcaspController@vincularGeral");
    Route::post('pcasp/editar-estruturais', "PcaspController@editarEstruturais");

    Route::get('pcasp/conta-corrente/{codcon}/{exercicio}', "PcaspContaCorrenteController@buscarPorPcasp");
    Route::post('pcasp/conta-corrente/salvar', "PcaspContaCorrenteController@adicionar");
    Route::post('pcasp/conta-corrente/remover', "PcaspContaCorrenteController@remover");

    //despesa
    Route::post('orcamentario/despesa/vincular', "PlanoOrcamentarioDespesaController@vincular");
    Route::post('orcamentario/despesa/vinculo-geral', "PlanoOrcamentarioDespesaController@vinculoGeral");
    Route::post('orcamentario/despesa/desvincular', "PlanoOrcamentarioDespesaController@desvincular");

    //receica
    Route::post('orcamentario/receita/vincular', "PlanoOrcamentarioReceitaController@vincular");
    Route::post('orcamentario/receita/vinculo-geral', "PlanoOrcamentarioReceitaController@vinculoGeral");
    Route::post('orcamentario/receita/desvincular', "PlanoOrcamentarioReceitaController@desvincular");

    Route::prefix('exclusao-geral')->group(function () {
        // receita
        Route::get(
            'orcamentario/receita/{estrutural}/{exercicio}',
            "PlanoOrcamentarioReceitaController@getReceitasSemUso"
        );

        Route::post('orcamentario/receita', "PlanoOrcamentarioReceitaController@exclusaoGeral");
        // despesa
        Route::get(
            'orcamentario/despesa/{estrutural}/{exercicio}',
            "PlanoOrcamentarioDespesaController@getDespesasSemUso"
        );
        Route::post('orcamentario/despesa', "PlanoOrcamentarioDespesaController@exclusaoGeral");


        Route::get('pcasp/{estrutural}/{exercicio}', 'PcaspController@contasSemUso');
        Route::post('pcasp', 'PcaspController@exclusaoGeral');
    });

    Route::get('pcasp/estrural-existe/{estrutural}/{exercicio}', "PcaspController@estruturalEstrutural");
});

Route::post('importar/msc', "MatrizController@importar");

Route::post('relatorio/atributos-plano-conas', "RelatorioConferenciaController@atributosPlanoContasMSC");


Route::prefix('BalancetesMensais')->group(function () {

    $path = "\App\Domain\Financeiro\Contabilidade\Controllers\\";
    Route::post('balancete-mensal-anexo1', $path . "BalancetesMensaisController@processarAnexo1");

});

Route::prefix('relatorio-tce')->group(function () {
    Route::post('exportar', "RelatorioTCEController@exportar");
    Route::post('buscar', "RelatorioTCEController@buscar");
});

Route::get('sistemas', "PcaspController@sistemas");
Route::get('sistema-conta', "PcaspController@sistemaConta");
