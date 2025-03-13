<?php
use \ECidade\Financeiro\Orcamento\Repository\RecursoRepository as Repository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];

try {
    db_inicio_transacao();

    switch ($parametros->acao) {
        case 'infoRecurso':
            $recurso = Repository::getByCodigo($parametros->idRecurso);
            $retorno->codigo = $recurso->getCodigo();
            $retorno->recurso = $recurso->getRecurso();
            $retorno->descricao = $recurso->getDescricao();
            $retorno->complemento = $recurso->getComplemento();

            $retorno->complementos =  Repository::getComplementos($recurso->getRecurso());
            break;

        case 'buscarComplementos':
            $fonteRecurso = '';

            if (!empty($parametros->idRecurso)) {
                $recurso = Repository::getByCodigo($parametros->idRecurso);
                $fonteRecurso = $recurso->getRecurso();
            }

            if (!empty($parametros->fonteRecurso)) {
                $fonteRecurso = $parametros->fonteRecurso;
            }

            if (empty($fonteRecurso)) {
                throw new Exception("Fonte de recurso não informada");
            }

            $retorno->complementos =  Repository::getComplementos($fonteRecurso);
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
