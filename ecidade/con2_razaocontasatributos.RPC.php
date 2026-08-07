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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta".".php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));

use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Repository\RazaoContaCorrente;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Relatorio\RazaoContaCorrente as Relatorio;


$json  = new services_json();
$parametro = $json->decode(str_replace("\\", "", $_POST['json']));

$retorno            = new stdClass();
$retorno->erro      = false;
$retorno->mensagem = "";

db_inicio_transacao();
db_putsession('DB_acessado', 2182);

$razaoContaCorrente = RazaoContaCorrente::getInstance();

try {
    switch ($parametro->exec) {
        case "gerarRelatorio":
            $filtros = new \stdClass();
            
            if (!empty($parametro->filtros->estrut_inicial)) {
                $filtros->estrutural = $parametro->filtros->estrut_inicial;
            }

            if (sizeof($parametro->filtros->atributos) > 0) {
                $filtros->setAtributos = true;
                $filtros->atributos = $parametro->filtros->atributos;
            }

            if (sizeof($parametro->filtros->contas) > 0) {
                $filtros->setContas = true;
                $filtros->contas    = array();
                foreach ($parametro->filtros->contas as $conta) {
                    $filtros->contas[] = $conta->sCodigo;
                }
            }

            if (sizeof($parametro->filtros->documentos) > 0) {
                $filtros->setDocumentos = true;
                $filtros->documentos    = array();
                foreach ($parametro->filtros->documentos as $documento) {
                   // $filtros->documentos[] = $documento->sCodigo;
                }
            }

            $dataInicial = new \DBDate($parametro->filtros->datainicial);
            $dataFinal   = new \DBDate($parametro->filtros->datafinal);

            $razaoContaCorrente->setFiltroUnidadeGestora($parametro->filtros->ug);
            $razaoContaCorrente->setContaCorrente($parametro->filtros->contaCorrente);
            $lancamentos = $razaoContaCorrente->buscarLancamentosRazaoContaCorrentePorPeriodo($dataInicial, $dataFinal, $filtros);
            $pdf = new Relatorio($lancamentos, $dataInicial, $dataFinal);
            $retorno->pdf = $pdf->imprimir();
            $retorno->mensagem = "Relatório gerado com sucesso.";
        break;

        case "buscarAtributos":
            $retorno->atributos = $razaoContaCorrente->buscarAtributosContasCorrente();
        break;

        case "buscarContasCorrente":
            $retorno->contas = $razaoContaCorrente->buscarContasCorrente();
        break;

        default :
          throw new Exception("Parâmetro inválido.");
        break;
    }
    db_fim_transacao(false);
} catch (Exception $oErro) {
    db_fim_transacao(true);
    $retorno->erro     = true;
    $retorno->mensagem = $oErro->getMessage();
}
$retorno->mensagem = urlencode($retorno->mensagem);
echo $json->encode($retorno);
