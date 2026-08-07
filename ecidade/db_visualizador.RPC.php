<?php

use ECidade\Core\Visualizador\Services\VisualizadorDocumentosService;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];

try {
    switch ($parametros->acao) {
        case 'buscar_imagens':
            if (empty($parametros->fileIds)) {
                throw new Exception("Nenhum arquivo para ser visualizado!");
            }
            if (!empty($parametros->descricoes)) {
                $descricoes = explode(',', $parametros->descricoes);
            }
            $codigos = explode(',', $parametros->fileIds);

            $service = new VisualizadorDocumentosService();
            $imagens = $service->getImages($codigos);

            $retorno->imagens = $imagens;
            break;
        default:
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

echo JSON::create()->stringify($retorno);
