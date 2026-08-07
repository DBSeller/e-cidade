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


use ECidade\Patrimonial\Acordo\AlteracaoContratado\Service\AlteracaoContratadoService;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];

try {
    db_inicio_transacao();

    switch ($parametros->acao) {
        case "buscarUltimaAlteracao":
            $alteraContratadoService = new AlteracaoContratadoService();
            $retorno->alteracoes = $alteraContratadoService->buscarUltimaAlteracao($parametros);
            break;

        case "buscarContratadoAtual":
            $alteraContratadoService = new AlteracaoContratadoService();
            $retorno->contratadoAtual = $alteraContratadoService->buscarContratadoAtual($parametros);
            break;

        case "salvarAlteracaoContratado":
            $alteraContratadoService = new AlteracaoContratadoService();
            $alteraContratadoService->salvarAlteracaoContratado($parametros);
            break;

        case "buscarEstrangeiro":
            $alteraContratadoService = new AlteracaoContratadoService();
            $retorno->documentoEstrangeiro = $alteraContratadoService->buscarEstrangeiro($parametros);
            break;

        case "exclusaoAditamentoContratado":
            db_inicio_transacao();
            $alteraContratadoService = new AlteracaoContratadoService();
            $alteraContratadoService->exclusaoAditamentoContratado($parametros);
            $retorno->mensagem = "Exclusão do aditamento realizada com sucesso";
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}
db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
