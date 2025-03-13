<?php

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory\AnexoVIFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Layout\AnexoVI;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('libs/db_libcontabilidade.php');
require_once modification('libs/db_liborcamento.php');

try {
    $parametros = (object)filter_input_array(INPUT_GET);

    $app = require_once ECIDADE_PATH . 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );


    $instituicoes = array_map(function ($codigo) {
        return InstituicaoRepository::getInstituicaoByCodigo($codigo);
    }, explode('-', $parametros->db_selinstit));

    $ano = db_getsession('DB_anousu');
    $relatorio = AnexoVIFactory::factory($ano);
    $relatorio->definirInstituicoes($instituicoes);
    $relatorio->definirPeriodo(new Periodo($parametros->periodo));
    $relatorio->definirParametros($parametros);
    $relatorio->definirAno($ano);

    $anexo = new AnexoVI();
    $anexo->setAnexo($relatorio);
    $anexo->emitir();
} catch (Exception $exception) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$exception->getMessage()}");
    exit;
}
