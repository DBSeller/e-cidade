<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];

use ECidade\Saude\Laboratorio\Service\ItemRequisicaoService;
use ECidade\Saude\Laboratorio\Repository\ItemRequisicaoRepository;

$itemRequisicaoService = new ItemRequisicaoService(new ItemRequisicaoRepository(new \cl_lab_requiitem()));
$requisicaoExame = new RequisicaoExame();

try {
    switch ($parametros->acao) {
        case 'buscarExamesPorRequisicao':
            $retorno->exames = $itemRequisicaoService->buscarSituacaoExamesPorRequisicao($parametros->requisicao);
            break;

        case 'regredirSituacao':
            foreach(JSON::create()->parse($parametros->itensRequisicao) as $itemRequisicao){
                if($itemRequisicao->situacao != $itemRequisicao->situacaoantiga){
                    $exame = new stdClass();
                    $exame->la21_i_codigo = $itemRequisicao->codigoitemrequisicao;
                    $exame->la21_c_situacao = $itemRequisicao->situacao;
                    $itemRequisicaoService->regredirSituacao($exame, $itemRequisicao->situacao, $itemRequisicao->situacaoantiga, $parametros->codigoRequisicao);
                }
            }
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
