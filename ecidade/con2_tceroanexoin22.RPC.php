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

use ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22\Factory as In22Factory;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");


$oParam = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$retorno = (object)array(
    'mensagem' => null,
    'erro' => false,
);

$ano = DB_getsession('DB_anousu');
db_inicio_transacao();
try {
    switch ($oParam->exec) {
        case 'processar':
            $relatorio = In22Factory::getInstance(
                $oParam->codigo_relatorio,
                $ano,
                $oParam->periodo,
                $oParam->instituicoes,
                db_getsession("DB_id_usuario")
            );
            $relatorio->setDataEmissao(new \DBDate(date('Y-m-d', db_getsession("DB_datausu"))));
            $nome = $relatorio->processar();
            $retorno->nome_arquivo = $nome;
            $retorno->caminho_arquivo = $nome;
            break;
    }
    db_fim_transacao(false);
} catch (Exception $exception) {
    db_fim_transacao(true);

    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

echo JSON::create()->stringify($retorno);
