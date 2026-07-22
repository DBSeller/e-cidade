<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

use ECidade\Educacao\Escola\Service\ConfirmacaoRematriculaService;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

$definicoes = array(
    'escola' => FILTER_VALIDATE_INT,
    'calendario' => FILTER_VALIDATE_INT,
    'turma' => FILTER_VALIDATE_INT,
    'acao' => FILTER_SANITIZE_STRING,
    'alunos' => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags' => FILTER_REQUIRE_ARRAY,
    )
);

$parametros = JSON::requestParameters($definicoes);

try {
    db_inicio_transacao();

    $retorno = new stdClass();
    $retorno->erro = false;

    $servico = new ConfirmacaoRematriculaService($parametros);

    switch ($parametros->acao) {
        case 'buscarAlunos':
            $retorno->alunos = $servico->buscarAlunosComRematriculaNaoConfirmada();
            break;
        case 'buscarConfirmados':
            $retorno->alunos = $servico->buscarAlunosComRematriculaConfirmada();
            break;
        case 'confirmarRematricula':
            $servico->confirmarRematricula();
            $retorno->mensagem = 'Confirmação de rematrícula efetuada com sucesso!';
            break;
        case 'desconfirmarRematricula';
            $servico->desconfirmarRematricula();
            $retorno->mensagem = 'Confirmações de rematrícula selecionadas excluidas com sucesso!';
            break;
        case 'emitirRelatorio':
            $retorno->arquivo = $servico->emitirRelatorio();
            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
