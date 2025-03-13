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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/JSON.php"));

require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBNumber.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));
require_once(modification("model/contabilidade/planoconta/SistemaConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/SubSistemaConta.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));

db_app::import("exceptions.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("patrimonio.*");
db_app::import("patrimonio.depreciacao.*");
db_app::import("recursosHumanos.RefactorProvisaoFerias");
db_app::import("orcamento.*");
db_app::import("Dotacao");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new db_stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$_SESSION["DB_desativar_account"] = true;
$iInstituicao = db_getsession("DB_instit");
$iAnoSessao   = db_getsession("DB_anousu");

try {

  switch ($oParam->exec) {

  	/**
  	 * Case para pegar os valores previsto para o ano, da orcreceita e orcdotacao
  	 */
  	case 'getDadosOrcamento' :

  		$oRetorno->nValorDotacao = Dotacao::getValorPrevistoNoAno($iAnoSessao,$iInstituicao);
  		$oRetorno->nValorReceita = ReceitaContabil::getValorPrevistoAno($iAnoSessao, $iInstituicao);

  		$oRetorno->iAnoSessao    = $iAnoSessao;

  		$oDaoAberturaexercicioorcamento = new cl_aberturaexercicioorcamento();
  		$sWhere                         = "     c104_instit     = {$iInstituicao} ";
  		$sWhere                        .= " and c104_ano        = {$iAnoSessao}      ";
  		$sWhere                        .= " and c104_processado = '{$oParam->lProcessados}' ";
  		$sSqlAberturaexercicioorcamento = $oDaoAberturaexercicioorcamento->sql_query_file(null, "1", null, $sWhere);
  		$rsAberturaexercicioorcamento   = $oDaoAberturaexercicioorcamento->sql_record($sSqlAberturaexercicioorcamento);
  		$oRetorno->lBloquearTela        = $oDaoAberturaexercicioorcamento->numrows > 0;

  	break;

    case 'processarAbertura':

      db_inicio_transacao();
      $oParam->lProcessar = $oParam->lProcessar == 'true';

      $lAberturaJaProcessada = AberturaExercicioOrcamento::possuiAberturaProcessadaParaAnoInstituicao($iAnoSessao, $iInstituicao);
      if ($oParam->lProcessar && $lAberturaJaProcessada) {
        throw new Exception("Abertura do exercício já processada para o ano {$iAnoSessao} na instituição logada.");
      }

      $oAbertura = AberturaExercicioOrcamento::getInstanciaPorAnoInstituicao($iAnoSessao, $iInstituicao);
      $oAbertura->setCodigoUsuario(db_getsession("DB_id_usuario"));
      $oAbertura->setCodigoInstituicao(db_getsession("DB_instit"));
      $oAbertura->setAno($iAnoSessao);
      $oAbertura->setDataProcessamento(new DBDate(date('Y-m-d', db_getsession("DB_datausu"))));
      $oAbertura->setProcessado($oParam->lProcessar);
      $oAbertura->salvar();

      $iSequencialAberturaExercicio = $oAbertura->getCodigo();
      $sObservacao                  = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);

      $iCodigoDocumento = $oParam->lProcessar ? 2003 : 2004;
      $oEventoContabil  = new EventoContabil($iCodigoDocumento, db_getsession("DB_anousu"));
      $receitas = \ReceitaContabil::getReceitasNoAno($iAnoSessao, $iInstituicao);
      if (count($receitas)> 0) {
          foreach ($receitas as $receita) {
              if (abs($receita->getValor()) == 0 ) {
                  continue;
              }
              executaLancamento($oEventoContabil,1, $receita, $iCodigoDocumento, abs($receita->getValor()), $iSequencialAberturaExercicio, $sObservacao);
          }
      }

        $iCodigoDocumento = $oParam->lProcessar ? 2001 : 2002;
        $oEventoContabil  = new EventoContabil($iCodigoDocumento, db_getsession("DB_anousu"));
        $dotacoes  = Dotacao::getDotacoesNoAno( $iAnoSessao, $iInstituicao );
        if (count($dotacoes) > 0) {
           foreach ($dotacoes as $dados) {
              if ($dados->o58_valor == 0 ) {
                  continue;
              }
              $dotacao = new Dotacao(null, null);
              $dotacao->setValor($dados->o58_valor);
              $dotacao->setCodigo($dados->o58_coddot);
              $dotacao->setAno($dados->o58_anousu);
              executaLancamento($oEventoContabil, 2, $dotacao, $iCodigoDocumento, $dotacao->getValor(), $iSequencialAberturaExercicio, $sObservacao);
          }
      }

      $oRetorno->message = 'Lançamentos processados com sucesso.';
      if (!$oParam->lProcessar) {
        $oRetorno->message = 'Lançamentos desprocessados com sucesso.';
      }

      db_fim_transacao(false);
      break;

  }

} catch (Exception $oErro) {

	$oRetorno->status  = 2;
	$oRetorno->message = $oErro->getMessage();
}

$oRetorno->message = urlencode($oRetorno->message);
unset($_SESSION["DB_desativar_account"]);
echo $oJson->encode($oRetorno);

/**
 * Executa lancamento
 *
 * @param         $tipo
 * @param ReceitaContabil|Dotacao  $objeto
 * @param integer $iCodigoDocumento
 * @param         $nValorLancamento
 * @param integer $iSequencialAberturaExercicio
 * @param string  $sObservacao
 * @return boolean true
 * @throws BusinessException
 */
function executaLancamento(\EventoContabil $oEventoContabil, $tipo, $objeto, $iCodigoDocumento, $nValorLancamento, $iSequencialAberturaExercicio, $sObservacao) {

	/**
	 * Descobre o codigo do documento pelo tipo
	 */

	$aLancamentos     = $oEventoContabil->getEventoContabilLancamento();
	$iCodigoHistorico = $aLancamentos[0]->getHistorico();

	unset($oDocumentoContabil);
	unset($aLancamentos);

	$oLancamentoAuxiliarAberturaExercicio = new LancamentoAuxiliarAberturaExercicioOrcamento();
	$oLancamentoAuxiliarAberturaExercicio->setObservacaoHistorico($sObservacao);
	$oLancamentoAuxiliarAberturaExercicio->setValorTotal($nValorLancamento);
	$oLancamentoAuxiliarAberturaExercicio->setHistorico($iCodigoHistorico);
	switch ($tipo) {
      case 1:
          $oLancamentoAuxiliarAberturaExercicio->setReceita($objeto);
          $oLancamentoAuxiliarAberturaExercicio->setAno($objeto->getAno());

          break;
      case 2:
          $oLancamentoAuxiliarAberturaExercicio->setDotacao($objeto);
          break;
  }
	$oLancamentoAuxiliarAberturaExercicio->setAberturaExercicioOrcamento($iSequencialAberturaExercicio);
	$oEventoContabil->executaLancamento($oLancamentoAuxiliarAberturaExercicio);

	return true;
}