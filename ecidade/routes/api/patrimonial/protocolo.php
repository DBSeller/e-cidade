<?php
Route::prefix('processo')->namespace('Processo')->middleware(["auth:api"])->group(function () {

    $routePrefix = 'processo';
    $path = "\App\Domain\Patrimonial\Protocolo\Controller\Processo\\";
    $controller = $path . "ProcessoController";

    Route::get($routePrefix, $controller . "@index");

    $routePrefix = 'processodocumento';
    $path = "\App\Domain\Patrimonial\Protocolo\Controller\Processo\\";
    $controller = $path . "ProcessoDocumentoController";

    Route::get($routePrefix, $controller . "@index");
    Route::post($routePrefix . '/download', $controller . "@download");
    Route::post($routePrefix . '/documentosPorProcesso', $controller . "@documentosPorProcesso");
    Route::post($routePrefix . '/documentosPorProcAndamInt', $controller . "@documentosPorProcAndamInt");

    /**
     * Andamento padrao
     */
    $routePrefixAndamentoPadrao = '{tipo_processo}/andamento-padrao/';
    $path = "\App\Domain\Patrimonial\Protocolo\Controller\Processo\AndamentoPadrao\\";

    /**
     * Campos dinamicos do andamento padrao
     */
    $routePrefixCampoDinamicos = $routePrefixAndamentoPadrao . 'campos-dinamicos';
    $controller = $path . "CamposDinamicosController";
    Route::get($routePrefixCampoDinamicos, $controller . "@index");
    Route::delete($routePrefixCampoDinamicos, $controller . "@delete");

    $routePrefixCampoDinamicos .= '/{ordem}';
    Route::post($routePrefixCampoDinamicos, $controller . "@salvar");

    /**
     * Respostas dos Campos dinamicos do andamento padrao de um processo
     */
    $controller = $path . "CamposDinamicosRespostaController";
    $routePrefixCampoDinamicos = 'andamento-padrao/campos-dinamicos/resposta';
    Route::post($routePrefixCampoDinamicos, $controller . "@salvar");
    Route::get($routePrefixCampoDinamicos, $controller . "@getUltimaResposta");

    /**
     * Campos dinamicos do andamento padrao de um processo
     */
    $controller = $path . "CamposDinamicosController";
    $routePrefixCampoDinamicos = 'andamento-padrao/campos-dinamicos/{codigo_processo}';
    Route::get($routePrefixCampoDinamicos, $controller . "@getByProcessoDepto");

});

Route::group(["middleware" => ["api", "clientCredential"]], function () {
    $path = "\App\Domain\Patrimonial\Protocolo\Controller\\";

    Route::get("cgm-cpf-cnpj", "{$path}CgmController@getCgmByCpfCnpj");
    Route::get("cgm", "{$path}CgmController@getByNumcgm");
    Route::get("rua-cep", "{$path}ProtocoloController@getRuaByCep");
    Route::get("rua-cep-municipio", "{$path}ProtocoloController@getRuaByCepMunicipio");
});

Route::group(["middleware" => ["api", "auth:api"]], function () {
    /**
     * Atividades a serem executadas em um tipo de processo
     */
    $path = "\App\Domain\Patrimonial\Protocolo\Controller\AtividadesExecucaoController";
    Route::get('/atividades-execucao', $path . "@index");
    Route::get('/atividades-execucao/tipo-processo/{tipoProcesso}', $path . "@TipoProcesso");
    Route::post('/atividades-execucao/excluir-vinculo', $path . "@excluirVinculo");
    Route::post('/atividades-execucao/vincular', $path . "@vincularAtividade");
    Route::post('/atividades-execucao/reordenar-vinculos', $path . "@reordenarVinculos");

    $path = "\App\Domain\Patrimonial\Protocolo\Controller\DocumentoAndamentoController";
    Route::post('/documentos/usuario', $path . "@index");
    Route::post('/documentos/conferir', $path . "@conferir");
    Route::post('/documentos/arquivar', $path . "@arquivar");
    Route::post('/documentos/conferir-em-lote', $path . "@conferirLote");
    Route::post('/documentos/devolver', $path . "@devolver");
    Route::post('/documentos/salvar-documento-assinado', $path . "@salvarDocumentoAssinado");
    Route::post('/documentos/atualizar-documento-assinado', $path . "@atualizarDocumentoAssinado");
    Route::post('/documentos/atividades-executadas', $path . "@buscarAtividadesExecutadas");
});

Route::group(["middleware" => ["api", "clientCredential"]], function () {
    $path = "\App\Domain\Patrimonial\Protocolo\Controller\DocumentoAndamentoController";
    Route::post('/documentos/buscar-por-identificador', $path . "@buscarPorIdentificador");
});
