<?php

use ECidade\Configuracao\RelatorioLegal\Servico\ColunaServico;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

db_inicio_transacao();

try {
    $resposta = new stdClass();
    $resposta->mensagem = '';
    $resposta->erro = false;
    $resposta->code = 200;

    $parametros = JSON::requestParameters();
    $servico = new ColunaServico($parametros);

    switch ($parametros->acao) {
        case 'salvar':
            $resposta->coluna = $servico->salvar()->toArray();
            $resposta->mensagem = 'Informações da coluna salvas com sucesso!';
            break;
        case 'excluir':
            $resposta->mensagem = $servico->excluir();
            break;
    }
} catch (Exception $exception) {
    $resposta->erro = true;
    $resposta->mensagem = $exception->getMessage();
    $resposta->code = $exception->getCode();
}

db_fim_transacao($resposta->erro);

echo JSON::create()->stringify($resposta);

exit($resposta->code);
