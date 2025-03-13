<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$json = new services_json();
$parametros = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$retorno = new stdClass();
$retorno->status = 1;
$retorno->message = '';

use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Repository\ContaReduzida;

try {

    db_inicio_transacao();

    switch ($parametros->exec) {
        case 'buscarContasReduzidasPorLancamento':
            $contasReduzidas = array();
            $codigoLancamento = $parametros->codigoLancamento;
            $contaReduzida = ContaReduzida::getInstance();
            $contaReduzida->getAnoLancamento($codigoLancamento);
            $contasLancamento = $contaReduzida->buscarContasReduzidasPorLancamento($codigoLancamento);

            foreach ($contasLancamento as $contas) {
                $contaDebito = new \stdClass();
                $contaCredito = new \stdClass();

                $contaCredito->numero = $contas->contaCredito;
                $contaDebito->numero = $contas->contaDebito;

                $contasReduzidas[] = $contaDebito;
                $contasReduzidas[] = $contaCredito;
            }
            
            $retorno->contasReduzidas = $contaReduzida->montarColecaoContaReduzidaInfoComplemetar($contasReduzidas, $codigoLancamento, db_getsession("DB_instit"));

            break;

        case 'excluirInformacoesComplementaresLancamento':
            if(!$parametros->codigoLancamento || !$parametros->contaReduzida) {
                throw new Exception("Código do lançamento ou reduzido da conta inválidos, impossível excluir valores das informações complementares.");
            }

            $contaReduzida = ContaReduzida::getInstance();
            $contaReduzida->excluirInformacaoComplementarLancamento($parametros->codigoLancamento, $parametros->contaReduzida);

            break;

        case 'salvar':
            $conta = $parametros->conta;

            if (empty($conta)) {
                throw new ParameterException("Conta não informada.");
            }

            $conta->existeConfiguracao = $conta->existeConfiguracao === 'true';

            $contaReduzida = ContaReduzida::getInstance();
            $contaReduzida->persistConlancamInfoComplementarValor($conta);
            $retorno->message = "Configurações salvas com sucesso.";
            break;
    }

    db_fim_transacao(false);
}catch (\Exception $erro) {
    db_fim_transacao(true);
    $retorno->status = 2;
    $retorno->message = $erro->getMessage();
}

$retorno->erro = $retorno->status == 2;
echo JSON::create()->stringify($retorno);