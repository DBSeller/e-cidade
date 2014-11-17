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


function __autoload($sClassName) {

  //echo "$sClassName\n";
  $aIncludeDirs = array();

  $aIncludeDirs[] = "model/";
  $aIncludeDirs[] = "model/ambulatorial/";
  $aIncludeDirs[] = "model/arrecadacao/";
  $aIncludeDirs[] = "model/arrecadacao/abatimento/";
  $aIncludeDirs[] = "model/cadastro/";
  $aIncludeDirs[] = "model/caixa/";
  $aIncludeDirs[] = "model/caixa/arquivos/";
  $aIncludeDirs[] = "model/caixa/slip/";
  $aIncludeDirs[] = "model/caixa/relatorios/conciliacaobancaria/";
  $aIncludeDirs[] = "model/compras/";
  $aIncludeDirs[] = "model/configuracao/";
  $aIncludeDirs[] = "model/integracao/transparencia/";
  $aIncludeDirs[] = "model/configuracao/avaliacao/";
  $aIncludeDirs[] = "model/configuracao/endereco/";
  $aIncludeDirs[] = "model/configuracao/inconsistencia/";
  $aIncludeDirs[] = "model/configuracao/inconsistencia/educacao/";
  $aIncludeDirs[] = "model/configuracao/mensagem/";
  $aIncludeDirs[] = "model/configuracao/notificacao/";
  $aIncludeDirs[] = "model/contabilidade/";
  $aIncludeDirs[] = "model/contabilidade/arquivos/";
  $aIncludeDirs[] = "model/contabilidade/arquivos/sigfis/";
  $aIncludeDirs[] = "model/contabilidade/contacorrente/";
  $aIncludeDirs[] = "model/contabilidade/lancamento/";
  $aIncludeDirs[] = "model/contabilidade/planoconta/";
  $aIncludeDirs[] = "model/contabilidade/relatorios/";
  $aIncludeDirs[] = "model/contabilidade/relatorios/sigfis/";
  $aIncludeDirs[] = "model/contabilidade/relatorios/dcasp/";
  $aIncludeDirs[] = "model/contabilidade/relatorios/rpps/";
  $aIncludeDirs[] = "model/contrato/";
  $aIncludeDirs[] = "model/contrato/mensageria/";
  $aIncludeDirs[] = "model/diversos/";
  $aIncludeDirs[] = "model/divida/";
  $aIncludeDirs[] = "model/educacao/";
  $aIncludeDirs[] = "model/educacao/avaliacao/";
  $aIncludeDirs[] = "model/educacao/ausencia/";
  $aIncludeDirs[] = "model/educacao/censo/";
  $aIncludeDirs[] = "model/educacao/classificacao/";
  $aIncludeDirs[] = "model/educacao/recursohumano/";
  $aIncludeDirs[] = "model/educacao/ocorrencia/";
  $aIncludeDirs[] = "model/educacao/progressaoparcial/";
  $aIncludeDirs[] = "model/educacao/relatorio/";
  $aIncludeDirs[] = "model/empenho/";
  $aIncludeDirs[] = "model/estoque/";
  $aIncludeDirs[] = "model/farmacia/";
  $aIncludeDirs[] = "model/laboratorio/";
  $aIncludeDirs[] = "model/financeiro/";
  $aIncludeDirs[] = "model/fiscal/";
  $aIncludeDirs[] = "model/fiscal/webservice/";
  $aIncludeDirs[] = "model/habitacao/";
  $aIncludeDirs[] = "model/issqn/";
  $aIncludeDirs[] = "model/juridico/";
  $aIncludeDirs[] = "model/material/";
  $aIncludeDirs[] = "model/orcamento/";
  $aIncludeDirs[] = "model/orcamento/programa/";
  $aIncludeDirs[] = "model/orcamento/suplementacao/";
  $aIncludeDirs[] = "model/patrimonio/";
  $aIncludeDirs[] = "model/patrimonio/material/";
  $aIncludeDirs[] = "model/patrimonio/material/relatorio/";
  $aIncludeDirs[] = "model/material/";
  $aIncludeDirs[] = "model/patrimonio/depreciacao/";
  $aIncludeDirs[] = "model/pessoal/";
  $aIncludeDirs[] = "model/pessoal/arquivos/";
  $aIncludeDirs[] = "model/pessoal/arquivos/dirf/";
  $aIncludeDirs[] = "model/pessoal/arquivos/siprev/";
  $aIncludeDirs[] = "model/pessoal/calculofinanceiro/";
  $aIncludeDirs[] = "model/pessoal/ferias/";
  $aIncludeDirs[] = "model/pessoal/folhapagamento/";
  $aIncludeDirs[] = "model/pessoal/relatorios/";
  $aIncludeDirs[] = "model/pessoal/std/";
  $aIncludeDirs[] = "model/protocolo/";
  $aIncludeDirs[] = "model/protocolo/cgm/";
  $aIncludeDirs[] = "model/recursosHumanos/";
  $aIncludeDirs[] = "model/social/";
  $aIncludeDirs[] = "model/social/cadastrounico/";
  $aIncludeDirs[] = "model/tfd/";
  $aIncludeDirs[] = "model/transporteescolar/";
  $aIncludeDirs[] = "model/veiculos/";
  $aIncludeDirs[] = "model/viradaIPTU/";
  $aIncludeDirs[] = "model/webservices/";
  $aIncludeDirs[] = "model/arrecadacao/boletos/";
  $aIncludeDirs[] = "model/impressoras/";


  $aArquivosCLasseErradas['clExpDadosColetores']                                  = 'model/agua/ExportaDadosColetores.model.php';
  $aArquivosCLasseErradas['clArqExpColetor']                                      = 'model/agua/ArquivoExportaColetor.model.php';
  $aArquivosCLasseErradas['logbaixaalvara']                                       = 'model/logBaixaAlvara.model.php';
  $aArquivosCLasseErradas['DBImpDownArquivoTexto']                                = 'model/dbImpDownArquivoTexto.model.php';
  $aArquivosCLasseErradas['importacaoMatriculaInep']                              = 'model/educacao/importacaoMatriculaInep2010.model.php';
  $aArquivosCLasseErradas['modelo4CM']                                            = 'model/modelo.4CM.php';
  $aArquivosCLasseErradas['impressaoM433TD']                                      = 'model/impressao.dieboldIM433TD.php';
  $aArquivosCLasseErradas['SolicitacaoMaterial']                                  = 'model/solicitacaoMaterial.model.php';
  $aArquivosCLasseErradas['logatividade']                                         = 'model/logAtividade.model.php';
  $aArquivosCLasseErradas['itemPacto']                                            = 'model/itempacto.model.php';
  $aArquivosCLasseErradas['calculoRetencaoIrrfFisica']                            = 'model/calculoRetencaoIrrf.model.php';
  $aArquivosCLasseErradas['ppaDespesa']                                           = 'model/ppadespesa.model.php';
  $aArquivosCLasseErradas['logcgm']                                               = 'model/logCgm.model.php';
  $aArquivosCLasseErradas['impressaoMP2100TH']                                    = 'model/impressao.bematechMP2100TH.php';
  $aArquivosCLasseErradas['impressaoOS214_plus']                                  = 'model/impressao.argoxOS214_plus.php';
  $aArquivosCLasseErradas['impressaoOS214']                                       = 'model/impressao.argoxOS214.php';
  $aArquivosCLasseErradas['tableDataManager']                                     = 'model/dataManager.php';
  $aArquivosCLasseErradas['LancamentoAuxiliarInscricaoRestosAPagarNaoProcessado'] = 'model/contabilidade/lancamento/LancamentoAuxiliarInscricaoRestosAPagarNaoProcessados.model.php';
  $aArquivosCLasseErradas['SingletonRegraDocumentoContabil']                      = 'model/contabilidade/SingletonDocumentoContabil.model.php';
  $aArquivosCLasseErradas['AlvaraCancelamento']                                   = 'model/issqn/AlvaraMovimentacaoCancelamento.model.php';
  $aArquivosCLasseErradas['AlvaraRenovacao']                                      = 'model/issqn/AlvaraMovimentacaoRenovacao.model.php';
  $aArquivosCLasseErradas['modeloEtiquetaBasica']                                 = 'model/modeloEtiqueBasica.php';
  $aArquivosCLasseErradas['DBVisualizadorImpressaoTexto']                         = 'model/dbVisualizadorImpressaoTexto.model.php';
  $aArquivosCLasseErradas['controleExamesLaboratorio']                            = 'model/controleexameslaboratorio.model.php';
  $aArquivosCLasseErradas['DBLayoutReader']                                       = 'model/dbLayoutReader.model.php';
  $aArquivosCLasseErradas['MeiArquivo']                                           = 'model/meiArquivo.model.php';
  $aArquivosCLasseErradas['DBLayoutLinha']                                        = 'model/dbLayoutLinha.model.php';
  $aArquivosCLasseErradas['encaminhamento']                                       = 'model/encaminhamentos.model.php';
  $aArquivosCLasseErradas['Recibo']                                               = 'model/recibo.model.php';
  $aArquivosCLasseErradas['_TaskSession']                                         = 'model/configuracao/Task.model.php';
  $aArquivosCLasseErradas['InstituicaoWebservice']                                = 'model/configuracao/InstituicaoWebService.model.php';
  $aArquivosCLasseErradas['logsocios']                                            = 'model/logSocios.model.php';
  $aArquivosCLasseErradas['modeloEtiqueta']                                       = 'model/dbModeloEtiqueta.model.php';
  $aArquivosCLasseErradas['loginscricao']                                         = 'model/logInscricao.model.php';
  $aArquivosCLasseErradas['modelo4CMPlus']                                        = 'model/modelo.4CM.Plus.php';

  /**
   * Opcoes alternativas aos diretorios padroes
   */
  $aExceptions[] = "std/";
  $aExceptions[] = "libs/";
  $aExceptions[] = "libs/exceptions/";

  foreach ($aExceptions as $sDiretorioExcecao) {

    $sArquivoExcecao = $sDiretorioExcecao . $sClassName . '.php';

    if (file_exists($sArquivoExcecao)) {

      require_once($sArquivoExcecao);
      return true;
    }

  }

  /**
   * Verificamos se o arquivo nao consta na lista de excess�es de arquivos
   */
  if (isset($aArquivosCLasseErradas[$sClassName])) {

    require_once $aArquivosCLasseErradas[$sClassName];
    return true;
  }

  if (substr($sClassName, 0, 3) == 'cl_') {

    $sClassNameDao = str_replace("cl_", "db_", $sClassName);
    require_once "classes/{$sClassNameDao}_classe.php";
    return true;

  } else {

    foreach ($aIncludeDirs as $sDirectory) {

      $sFile = "{$sDirectory}{$sClassName}.model.php";

      if (file_exists($sFile)) {

        require_once($sFile);
        break;
      }

      $sFile = "{$sDirectory}{$sClassName}.service.php";

      if (file_exists($sFile)) {

        require_once($sFile);
        break;
      }
    }
  }

  return true;
}