<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

db_inicio_transacao();

try {

  switch ($oParam->exec) {

    case 'getAtributosDoExame':

      $oRequisicao           = new RequisicaoExame($oParam->requisicao);
      $oExame                = $oRequisicao->getExame();
      $oResultadoExame       = $oRequisicao->getResultado();
      $aAtributos            = $oExame->getAtributos();
      $oRetorno->sObservacao = urlencode( $oRequisicao->getObservacao() );
      $oRetorno->atributos   = array();

      foreach( $aAtributos as $oAtributo ) {

        $oAtributoStd                    = new stdClass();
        $oAtributoStd->codigo            = urlencode($oAtributo->getCodigo());
        $oAtributoStd->descricao         = urlencode($oAtributo->getNome());
        $oAtributoStd->tipo              = urlencode($oAtributo->getTipo());
        $oAtributoStd->nivel             = urlencode($oAtributo->getNivel());
        $oAtributoStd->tiporeferencia    = $oAtributo->getTipoReferencia();
        $oAtributoStd->valorpercentual   = '';
        $oAtributoStd->valorabsoluto     = '';
        $oAtributoStd->codigoreferencia  = '';
        $oReferenciaSalva                = null;

        if ($oResultadoExame->getValorDoAtributo($oAtributo) != '') {

          $oResultadoAtributo            = $oResultadoExame->getValorDoAtributo($oAtributo);
          $oAtributoStd->valorabsoluto   = $oResultadoAtributo->getValorAbsoluto();
          $oAtributoStd->valorpercentual = $oResultadoAtributo->getValorPercentual();
          $oReferenciaSalva              = $oResultadoAtributo->getFaixaUtilizada();
        }

        $oAtributoStd->referencia      = '';
        if ($oAtributoStd->tiporeferencia != '') {

          $oReferenciasAtributo = $oReferenciaSalva;

          if( empty( $oReferenciasAtributo ) || $oReferenciasAtributo->getCodigo() == '' ) {
            $oReferenciasAtributo = $oAtributo->getValoresDeReferenciaParaExame($oRequisicao);
          }

          $iCasasDecimais = null;

          if( $oReferenciasAtributo instanceof AtributoValorReferenciaNumerico ) {
            $iCasasDecimais = $oReferenciasAtributo->getCasasDecimaisApresentacao();
          }

          $oReferenciaStd           = new stdClass();
          $oReferenciaStd->unidade  = '';

          if ($oAtributo->getUnidadeMedida() != null) {
           $oReferenciaStd->unidade  = urlencode($oAtributo->getUnidadeMedida()->getNome());
          }

          $oReferenciaStd->faixanormalminimo  = '';
          $oReferenciaStd->faixanormalmaximo  = '';
          $oReferenciaStd->faixaabsurdoinicio = '';
          $oReferenciaStd->faixaasurdomaximo  = '';
          $oReferenciaStd->tipocalculo        = '';
          $oReferenciaStd->atributobase       = '';
          $oReferenciaStd->baseparacalculo    = false;
          $oReferenciaStd->fixo               = '';
          $oReferenciaStd->selecoes           = array();
          $oReferenciaStd->tipo               = '';

          switch ($oAtributo->getTipoReferencia()) {

            case AtributoExame::REFERENCIA_NUMERICA:

              if (empty($oReferenciasAtributo)) {
                continue;
              }

              $oReferenciaStd->faixanormalminimo  = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oReferenciasAtributo->getValorMinimo() );
              $oAtributoStd->codigoreferencia     = $oReferenciasAtributo->getCodigo();
              $oReferenciaStd->faixanormalmaximo  = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oReferenciasAtributo->getValorMaximo() );
              $oReferenciaStd->faixaabsurdoinicio = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oReferenciasAtributo->getValorAbsurdoMinimo() );
              $oReferenciaStd->faixaasurdomaximo  = MascaraValorAtributoExame::mascarar( $iCasasDecimais, $oReferenciasAtributo->getValorAbsurdoMaximo() );
              $oReferenciaStd->tipocalculo        = $oReferenciasAtributo->getTipoCalculo();

              if ($oReferenciasAtributo->getAtributoBase() != '') {
                $oReferenciaStd->atributobase = $oReferenciasAtributo->getAtributoBase()->getCodigo();
              }

              $oReferenciaStd->tipo     = AtributoExame::REFERENCIA_NUMERICA;
              $oAtributoStd->referencia = $oReferenciaStd;
              break;

            case AtributoExame::REFERENCIA_FIXA:

              $oReferenciaStd->fixo     = $oReferenciasAtributo->getTamanho();
              $oReferenciaStd->tipo     = AtributoExame::REFERENCIA_FIXA;
              $oAtributoStd->referencia = $oReferenciaStd;
              break;

            case AtributoExame::REFERENCIA_SELECIONAVEL:

               $oReferenciaStd->tipo = AtributoExame::REFERENCIA_SELECIONAVEL;
               foreach ($oReferenciasAtributo->getReferenciasSelecionaveis() as $oReferencia) {

                 $oSelecao                   = new stdClass();
                 $oSelecao->codigo           = $oReferencia->getCodigo();
                 $oSelecao->nome             = urlencode($oReferencia->getDescricao());
                 $oReferenciaStd->selecoes[] = $oSelecao;
               }

               $oAtributoStd->referencia = $oReferenciaStd;

              break;
          }
        }

        $oRetorno->atributos[] = $oAtributoStd;
      }

      foreach ($oRetorno->atributos as $oAtributo) {

        if ($oAtributo->referencia == '') {
          continue;
        }

        if ($oAtributo->referencia->tipo == AtributoExame::REFERENCIA_NUMERICA) {

          foreach ($oRetorno->atributos as $oAtributoVerificar) {

            if ($oAtributoVerificar->referencia == '') {
              continue;
            }

            if ($oAtributoVerificar->referencia->atributobase != '' &&
               $oAtributoVerificar->referencia->atributobase == $oAtributo->codigo ) {

              $oAtributo->referencia->baseparacalculo = true;
              $oAtributo->valorpercentual             = '100';
              break;
            }
          }
        }
      }
      break;

    case 'salvarResultadoExame':

      $oRequisicao     = new RequisicaoExame($oParam->iCodigoExame);
      $oExame          = $oRequisicao->getExame();
      $oResultadoExame = $oRequisicao->getResultado();

      foreach ($oParam->aAtributos as $oAtributoLancado) {

        $oAtributo         = AtributoExameRepository::getByCodigo($oAtributoLancado->iCodigoAtributo);
        $oResultadoLancado = $oResultadoExame->getValorDoAtributo($oAtributo);
        if (empty($oResultadoLancado)) {

          $oResultadoLancado = new ResultadoExameAtributo();
          $oResultadoLancado->setAtributo($oAtributo);
          $oResultadoExame->adicionarResultadoParaAtributo($oResultadoLancado);
        }

        $nValorAbsoluto = db_stdClass::normalizeStringJsonEscapeString($oAtributoLancado->nValorAbsoluto);
        $oResultadoLancado->setValorPercentual($oAtributoLancado->nValorPercentual);
        $oResultadoLancado->setValorAbsoluto($nValorAbsoluto);

        if (!empty($oAtributoLancado->iCodigoReferencia)) {
          $oResultadoLancado->setFaixaUtilizada(new AtributoValorReferenciaNumerico($oAtributoLancado->iCodigoReferencia));
        }
      }

      $oResultadoExame->salvar();
      $oRequisicao->setSituacao(RequisicaoExame::LANCADO);
      $oRequisicao->setObservacao( db_stdClass::normalizeStringJsonEscapeString( $oParam->sObservacao ) );
      $oRequisicao->salvar();
      db_fim_transacao();
      $oRetorno->message = 'Resultados salvo com sucesso.';
      break;
  }
} catch (BusinessException $eErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);