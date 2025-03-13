<?php

use ECidade\Educacao\Escola\Resource\ComponenteCurricularResource;
use ECidade\Educacao\Escola\Service\ComponenteCurricularService;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));


$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];
try {
    db_inicio_transacao();
    switch ($parametros->acao) {
        case 'get':
            $service = new ComponenteCurricularService();
            $retorno->disciplinas = ComponenteCurricularResource::toArray($service->get());
            break;
        case 'salvar':
            $service = new ComponenteCurricularService();
            $disciplina = $service->salvar($parametros);
            $retorno->disciplina = array_shift(ComponenteCurricularResource::toArray([$disciplina]));
            $retorno->mensagem = "Componente curricular salvo com sucesso.";
            break;

        case 'excluir':
            $service = new ComponenteCurricularService();
            $disciplina = $service->excluir($parametros);
            $retorno->mensagem = "Componente curricular excluído com sucesso.";
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
