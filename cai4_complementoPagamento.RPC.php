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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oParam = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->message = '';
$oRetorno->erro = false;

try {

    db_inicio_transacao();

    switch ($oParam->exec) {
        case 'getDadosMovimento':
            if (empty($oParam->codigo_movimento)) {
                throw new Exception("Código do Movimento não informado.");
            }

            $buscaHistorico = <<<SQL_BUSCA
                select e50_obs as historico
                  from pagordem
                       join empord on empord.e82_codord = pagordem.e50_codord
                       join empempenho on empempenho.e60_numemp = pagordem.e50_numemp
                 where e82_codmov = {$oParam->codigo_movimento}
SQL_BUSCA;

            $resBusca = db_query($buscaHistorico);
            $movimentoEmpenho = pg_num_rows($resBusca) > 0;
            if (!$resBusca) {
                throw new Exception('Ocorreu um erro ao consultar o histórico do movimento.');
            }

            if ($movimentoEmpenho === false) {
                $resBusca = db_query("select k17_texto as historico from empageslip join slip on e89_codigo = k17_codigo where e89_codmov = {$oParam->codigo_movimento}");
                if (!$resBusca) {
                    throw new Exception("Não foi possível consultar os dados do slip.");
                }
            }


            $stdDados = db_utils::fieldsMemory($resBusca, 0);
            $oRetorno->nota_liquidacao = (object)array(
                'historico' => $stdDados->historico,
            );
            $hash = "HISTORICO_PAGAMENTO_{$oParam->codigo_movimento}";
            if (!empty($_SESSION[$hash])) {
                $oRetorno->nota_liquidacao->historico = $_SESSION[$hash];
            }

            break;

        case "aplicarHistorico":
            if (empty($oParam->codigo_movimento)) {
                throw new Exception("Código do Movimento não informado.");
            }
            $oParam->historico = trim($oParam->historico);
            if (empty($oParam->historico)) {
                throw new Exception('Histórico é de preenchimento obrigatório.');
            }
            $hash = "HISTORICO_PAGAMENTO_{$oParam->codigo_movimento}";
            unset($_SESSION[$hash]);
            $_SESSION[$hash] = $oParam->historico;

            $oRetorno->message = 'Histórico aplicado com sucesso.';
            break;

    }

    db_fim_transacao(false);

} catch (Exception $e) {

    db_fim_transacao(true);

    $oRetorno->message = $e->getMessage();
    $oRetorno->erro = true;
}

echo JSON::create()->stringify($oRetorno);
