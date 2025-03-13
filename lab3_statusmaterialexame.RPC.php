<?php

use ECidade\Saude\Laboratorio\Service\RequisicaoLaboratorialService;
use ECidade\Saude\Laboratorio\Repository\RequisicaoLaboratorialRepository;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('model/laboratorio/RequisicaoExame.model.php');

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

$serviceRequisicao = new RequisicaoLaboratorialService(new RequisicaoLaboratorialRepository(new \cl_lab_requisicao()));

try {
    db_inicio_transacao();
    switch($parametros->acao) {
        case 'buscaMateriaisPorRequisicao':
            $retorno->listaMateriais = $serviceRequisicao->getMateriasPorRequisicao($parametros->codigoRequisicao);
            break;
        case 'buscaExamesPorRequisicao':
            $retorno->listaExames = $serviceRequisicao->getExamesPorRequisicao($parametros->codigoRequisicao);
            break;
        case 'buscaExamesPorMaterialRequisicao':
            $retorno->listaExames = $serviceRequisicao->getExamesPorMaterialRequisicao(
                $parametros->codigoRequisicao,
                $parametros->codigoMaterial
            );

            break;
        case 'buscaDadosPorCodigoBarras':
            $requisicao = $serviceRequisicao->getRequisicaoLaboratorial($parametros->codigoRequisicao);
            $solicitante = $serviceRequisicao->getSolicitanteRequisicao($parametros->codigoRequisicao);

            $retorno->solicitante = $solicitante->z01_v_nome;
            $retorno->requisicao = $requisicao->toArray();

            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
