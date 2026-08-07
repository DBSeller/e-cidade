<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];

use ECidade\Saude\Laboratorio\Service\GrupoService;
use ECidade\Saude\Laboratorio\Repository\GrupoRepository;
use ECidade\Saude\Laboratorio\Repository\GrupoLaboratorioRepository;
use ECidade\Saude\Laboratorio\Repository\GrupoExameRepository;

$grupoService = new GrupoService(new GrupoRepository(new \cl_lab_grupo()), new GrupoLaboratorioRepository(new \cl_lab_labgrupoexame()));

try {
    switch ($parametros->acao) {
        case 'buscarGrupos':
            $retorno->grupos = $grupoService->buscarGrupos();
            break;
        case 'salvar':
            $grupoService->salvar($parametros);
            break;
        case 'excluirGrupo':
            $grupoService->excluirGrupo($parametros->codigo);
            break;
        
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
