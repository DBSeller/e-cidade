<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];

use ECidade\Saude\Laboratorio\Service\GrupoExameService;
use ECidade\Saude\Laboratorio\Repository\GrupoExameRepository;
use ECidade\Saude\Laboratorio\Repository\SetorExameRepository;

$grupoExamesService = new GrupoExameService(new GrupoExameRepository(new \cl_lab_grupoexame()), new SetorExameRepository(new \cl_lab_setorexame()));

try {
    switch ($parametros->acao) {
        case 'buscarExamesGrupo':
            $retorno->grupoExames = $grupoExamesService->buscarGrupoExames($parametros);
            break;
        case 'adicionarExamesAoGrupo':
            $grupoExamesService->salvar($parametros);
            break;
        case 'excluirVinculoExameGrupo':
            $grupoExamesService->excluirExameGrupo($parametros->codigo);
            break;
        
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
