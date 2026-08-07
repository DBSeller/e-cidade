<?php

use Illuminate\Support\Facades\Route;

Route::prefix('atendimento')
    ->group(function () {
        /**
         * Autenticação para usuarios
         */
        Route::middleware(["auth:api"])->group(function () {
            Route::prefix('atendimento')
                ->namespace('Atendimento')
                ->group(function () {
                    $controller = "AtendimentoController";
                    Route::get("/", "{$controller}@index");
                    Route::post('/buscarProcessosOuvidoria', "{$controller}@buscarProcessosOuvidoria");
                    Route::post('/buscarSolicitacaoOuvidoria', "{$controller}@buscarSolicitacaoOuvidoria");
                    Route::post(
                        '/buscarSolicitacaoOuvidoriaPorProcesso',
                        "{$controller}@buscarSolicitacaoOuvidoriaPorProcesso"
                    );
                    Route::middleware(["legacySession"])->group(function () use ($controller) {
                        Route::post('/aprovarProcessoOuvidoria', "{$controller}@aprovarProcessoOuvidoria");
                        Route::post('/rejeitarProcessoOuvidoria', "{$controller}@rejeitarProcessoOuvidoria");
                        Route::post('/existeInscricao', "{$controller}@existeInscricaoAlvara");
                    });
                });
        });

        /**
         * Autenticação para usuário ou aplicação
         */
        Route::middleware(["api", "clientCredential"])->group(function () {
            Route::prefix("processo-eletronico")
                ->namespace('ProcessoEletronico')
                ->group(function () {
                    $controller = "ProcessoEletronicoController";
                    Route::get("/mensagens/{numero_processo}", "{$controller}@mensagens");
                    Route::post("/savevisualizacao/{numero_processo}", "{$controller}@saveVisualizacao");
                    Route::get("/menu", "{$controller}@menu");
                    Route::post("/solicitacao-atendimento", "{$controller}@solicitacaoDeAtendimento");
                    Route::get("/servidor/cpf", "{$controller}@consultaServidor");
                    Route::get("/dependente/servidor/cpf", "{$controller}@consultarDependentesServidor");
                    Route::get(
                        "/servidor/{cpf}/tipoprocesso/{tipoProcesso}",
                        "{$controller}@verificaServidorPossuiPermissaoRecadastramento"
                    );
                    Route::get("/atendimentos", "{$controller}@consultaAtendimentosIds");
                    Route::get("/detalhe-processo/{codigoProcesso}", "{$controller}@detalheProcesso");
                });

            Route::prefix("servidor")
                ->namespace('ProcessoEletronico')
                ->group(function () {
                    $controller = "ServidorController";
                    Route::get("matriculas/{cpf}", $controller . "@getMatriculas");
                    Route::get("assentamentos/{matricula}", $controller . "@getAssentamentos");
                    Route::get("averbacoes/{matricula}", $controller . "@getAverbacoes");
                    Route::get("ferias/{matricula}", $controller . "@getFerias");
                    Route::get("anos-trabalhados/{matricula}", $controller . "@getAnosTrabalhados");
                    Route::get("comprovante-irrf/{matricula}/ano/{ano}", $controller . "@getComprovanteIRRF");
                });
        });
    });
