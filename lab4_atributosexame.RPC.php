<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");


$oJson        = new services_json();
$oParam       = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
db_inicio_transacao();
try {

  switch ($oParam->exec) {

    case 'getReferenciasAtributo':

      if (!empty($oParam->iCodigoAtributo)) {

        $oAtributoExame = new AtributoExame($oParam->iCodigoAtributo);

        $oDadosAtributo                    = new stdClass();
        $oDadosAtributo->nome              = urlencode($oAtributoExame->getNome());
        $oDadosAtributo->codigo            = urlencode($oAtributoExame->getCodigo());
        $oDadosAtributo->codigo_unidade    = '';
        $oDadosAtributo->descricao_unidade = '';

        $oUnidadeMedida  = $oAtributoExame->getUnidadeMedida();
        if (!empty($oUnidadeMedida)) {

          $oDadosAtributo->codigo_unidade    = $oUnidadeMedida->getCodigo();
          $oDadosAtributo->descricao_unidade = url_encode($oUnidadeMedida->getNome());
        }
        $oRetorno->atributo= $oDadosAtributo;
      }
      break;

    case 'getValoresReferenciaAtributo' :

      if (!empty($oParam->iCodigoAtributo)) {

        $oRetorno->aValoresNumericos = array();

        $oAtributoExame     = new AtributoExame($oParam->iCodigoAtributo);
        $aValoresReferencia = $oAtributoExame->getValoresReferencia();
        foreach ($aValoresReferencia as $oValorReferencia) {

          $oValor                = new stdClass();

          $oLimiteIdade          = new stdClass();
          $oLimiteIdade->anos    = '';
          $oLimiteIdade->meses   = '';
          $oLimiteIdade->dias    = '';
          $oValorIdade           = $oValorReferencia->getIdadeFinal();
          if (!empty($oValorIdade)) {

            $oLimiteIdade->anos  = $oValorReferencia->getIdadeFinal()->getYears();
            $oLimiteIdade->meses = $oValorReferencia->getIdadeFinal()->getMonths();
            $oLimiteIdade->dias  = $oValorReferencia->getIdadeFinal()->getDays();
          }

          $iCasasDecimais        = $oValorReferencia->getCasasDecimaisApresentacao();
          $oValor->valor_inicial = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oValorReferencia->getValorMinimo());
          $oValor->valor_final   = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oValorReferencia->getValorMaximo());
          $oValor->sexo          = $oValorReferencia->getSexos();
          $oValor->codigo        = $oValorReferencia->getCodigo();
          $oValor->limite_idade  = $oLimiteIdade;

          $oRetorno->aValoresNumericos[] = $oValor;
        }
      }
      break;

    case "salvarReferenciaNumerica":

      if (empty($oParam->iCodigoReferencia)) {

        $oDaoValorReferencia = new cl_lab_valorreferencia();
        /**
         * Verificamos se já não existe nenhuma referencia cadastrada
         */
        $sSqlVerificaReferencia = $oDaoValorReferencia->sql_query_file(null, "la27_i_codigo",
                                                                       null, "la27_i_atributo = {$oParam->iAtributo}"
                                                                      );

        $rsVerificaReferencia = $oDaoValorReferencia->sql_record($sSqlVerificaReferencia);
        if ($oDaoValorReferencia->numrows == 0) {

          $oDaoValorReferencia->la27_i_atributo = $oParam->iAtributo;
          $oDaoValorReferencia->la27_i_unidade  = $oParam->iUnidadeMedida;
          $oDaoValorReferencia->incluir(null);
          if ($oDaoValorReferencia->erro_status == 0) {
            throw new BusinessException ("Erro ao incluir dados da referência");
          }
        }
      }
      $oDaoValorReferenciaGrupo = new cl_lab_valorrefselgrupo();
      $oDaoValorReferenciaAlfa  = new cl_lab_tiporeferenciaalfa();
      $oAtributo                = new AtributoExame($oParam->iAtributo);

      /**
       * excluimos as referencias Selecionaveis
       */
      $sWhereGrupo  = "select la29_i_codigo ";
      $sWhereGrupo .= "  from lab_tiporeferenciaalfa ";
      $sWhereGrupo .= "  where la29_i_valorref = {$oAtributo->getCodigoReferencia()}";
      $oDaoValorReferenciaGrupo->excluir(null, "la51_i_referencia in ({$sWhereGrupo})");
      if ($oDaoValorReferenciaGrupo->erro_status == 0) {
        throw new BusinessException ("Erro ao remover dados da referência selecionável");
      }

      $oDaoValorReferenciaAlfa->excluir(null, "la29_i_valorref = {$oAtributo->getCodigoReferencia()}");
      if ($oDaoValorReferenciaAlfa->erro_status == 0) {
        throw new BusinessException ("Erro ao remover dados  da referência alfa numerica");
      }
      $oReferencia      = $oParam->oReferencia;
      $oValorReferencia = new AtributoValorReferenciaNumerico();
      $oValorReferencia->setValorMinimo($oReferencia->iValorMinimo);
      $oValorReferencia->setValorMaximo($oReferencia->iValorMaximo);
      $oValorReferencia->setValorAbsurdoMinimo($oReferencia->iValorAbsurdoMinimo);
      $oValorReferencia->setValorAbsurdoMaximo($oReferencia->iValorAbsurdoMaximo);
      $oValorReferencia->setCasasDecimaisApresentacao($oReferencia->iCasasDecimais);
      foreach($oReferencia->aSexos as $sSexo) {
        $oValorReferencia->adicionarSexo($sSexo);
      }

      $sIntervalor = "{$oReferencia->iDiasInicial} days {$oReferencia->iDiasInicial} months {$oReferencia->iAnosInicial} years";
      $oValorReferencia->setIdadeInicial(new DBInterval($sIntervalor));

      $sIntervalo= "{$oReferencia->iDiasFinal} days {$oReferencia->iDiasFinal} months {$oReferencia->iAnosFinal} years";
      $oValorReferencia->setIdadeFinal(new DBInterval($sIntervalo));
      if ($oReferencia->iTipoCalculo > 0) {

        $oValorReferencia->setTipoCalculo($oReferencia->iTipoCalculo);
        $oValorReferencia->setAtributoBase(new AtributoExame($oReferencia->iAtributoBase));
      }
      $oValorReferencia->salvar($oAtributo->getCodigoReferencia());
      db_fim_transacao();
      $oRetorno->message = urlencode("Referência salva com sucesso.");
      break;

    case 'removerReferenciaNumerica':

      $oValorReferencia = new AtributoValorReferenciaNumerico($oParam->iCodigo);
      $oValorReferencia->remover();
      db_fim_transacao();
      $oRetorno->message = urlencode("Referência removida com sucesso.");
      break;

  }
}  catch (BusinessException $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);