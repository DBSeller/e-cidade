<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];

use ECidade\Saude\Laboratorio\Service\GrupoLaboratorioService;
use ECidade\Saude\Laboratorio\Repository\GrupoLaboratorioRepository;
use ECidade\Saude\Laboratorio\Repository\GrupoExameRepository;

$gruposLaboratorioService = new GrupoLaboratorioService(new GrupoLaboratorioRepository(new \cl_lab_labgrupoexame()),new GrupoExameRepository(new \cl_lab_grupoexame()));

try {
    switch ($parametros->acao) {
        case 'buscarGruposLaboratorio':
            $retorno->gruposLaboratorio = $gruposLaboratorioService->buscarGruposLaboratorio($parametros);
            break;
        case 'adicionarGrupoLaboratorio':
            $gruposLaboratorioService->salvar($parametros);
            break;
        case 'excluirGrupoLaboratorio':
            $gruposLaboratorioService->excluirGrupoLaboratorio($parametros);
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
