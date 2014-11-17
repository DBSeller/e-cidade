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

require_once "ArquivoBase.model.php";
require_once "ArquivoConfiguracaoTCEAC.model.php";

class ArquivoPartida extends ArquivoBase {

  public function __construct(DBDate $oDataInicial, DBDate $oDataFinal) {
    parent::__construct($oDataInicial, $oDataFinal);

    $this->gerarArquivo();
  }

  /**
   * Monta a linha da Conta Corrente do Arquivo
   *
   * @param  ContaCorrenteDetalhe $oContaCorrenteDetalhe
   * @param  stdclass $oLancamento
   * @throws Exception
   * @return string
   */
  private function getLinhaContaCorrente(ContaCorrenteDetalhe $oContaCorrenteDetalhe, &$oLancamento) {

    $sContaCorrente = '';

    switch ($oLancamento->c19_contacorrente) {

      case 100:
        $iRecurso = ArquivoConfiguracaoTCEAC::getInstancia()->getRecursoPorCodigo( $oContaCorrenteDetalhe->getRecurso()->getCodigo());

        if (empty($iRecurso)) {
          throw new Exception("Código do recurso não encontrado para o recurso {$oContaCorrenteDetalhe->getRecurso()->getCodigo()}.");
        }

        $sContaCorrente .= substr($oContaCorrenteDetalhe->getEstrutural(), 0, 10);
        $sContaCorrente .= str_pad( $iRecurso, 3, 0, STR_PAD_LEFT );
        break;

      case 101:

        $iRecurso = ArquivoConfiguracaoTCEAC::getInstancia()->getRecursoPorCodigo( $oContaCorrenteDetalhe->getRecurso()->getCodigo());

        if (empty($iRecurso)) {
          throw new Exception("Código do recurso não encontrado para o recurso {$oContaCorrenteDetalhe->getRecurso()->getCodigo()}.");
        }

        $oDotacao    = $oContaCorrenteDetalhe->getDotacao();
        $sEstrutural = $oContaCorrenteDetalhe->getEstrutural();

        $sContaCorrente .= str_pad($oDotacao->getFuncao(), 2, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oDotacao->getSubFuncao(), 3, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oDotacao->getPrograma(), 4, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oDotacao->getProjAtiv(), 6, 0, STR_PAD_LEFT);
        $sContaCorrente .= substr($sEstrutural, 1, 1);
        $sContaCorrente .= substr($sEstrutural, 2, 1);
        $sContaCorrente .= substr($sEstrutural, 3, 2);
        $sContaCorrente .= substr($sEstrutural, 5, 2);
        $sContaCorrente .= str_pad( $iRecurso, 3, 0, STR_PAD_LEFT );
        $sContaCorrente .= str_pad($oDotacao->getOrgao(), 6, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oDotacao->getUnidade(), 6, 0, STR_PAD_LEFT);
        break;

      case 102:

        $iRecurso = ArquivoConfiguracaoTCEAC::getInstancia()->getRecursoPorCodigo( $oContaCorrenteDetalhe->getRecurso()->getCodigo());

        if (empty($iRecurso)) {
          throw new Exception("Código do recurso não encontrado para o recurso {$oContaCorrenteDetalhe->getRecurso()->getCodigo()}.");
        }

        $oDotacao    = $oContaCorrenteDetalhe->getDotacao();
        $oEmpenho    = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oLancamento->c75_numemp);
        $oCgm        = $oEmpenho->getFornecedor();
        $sEstrutural = $oContaCorrenteDetalhe->getEstrutural();

        $sContaCorrente .= str_pad($oDotacao->getFuncao(), 2, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oDotacao->getSubFuncao(), 3, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oDotacao->getPrograma(), 4, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oDotacao->getProjAtiv(), 6, 0, STR_PAD_LEFT);
        $sContaCorrente .= substr($sEstrutural, 1, 1);
        $sContaCorrente .= substr($sEstrutural, 2, 1);
        $sContaCorrente .= substr($sEstrutural, 3, 2);
        $sContaCorrente .= substr($sEstrutural, 5, 2);
        $sContaCorrente .= str_pad( $iRecurso, 3, 0, STR_PAD_LEFT );
        $sContaCorrente .= str_pad($oDotacao->getOrgao(), 6, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oDotacao->getUnidade(), 6, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oEmpenho->getAnoUso(), 4, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oEmpenho->getCodigo(), 12, 0, STR_PAD_LEFT);

        $iTipoEmpenho = ArquivoConfiguracaoTCEAC::getInstancia()->getTipoEmpenhoPorCodigo($oEmpenho->getTipoEmpenho());

        if (empty($iTipoEmpenho)) {
          throw new Exception("Tipo de empenho não encontrado para o empenho {$oEmpenho->getCodigo()}/{$oEmpenho->getAnoUso()}.");
        }

        $sContaCorrente .= $iTipoEmpenho;

        $sCpfCnpj    = '';
        $iTipoCredor = 1;

        if ($oCgm->isFisico()) {

          $sCpfCnpj = $oCgm->getCpf();
        } else {

          $sCpfCnpj    = $oCgm->getCnpj();
          $iTipoCredor = 2;
        }

        $sContaCorrente .= str_pad( $sCpfCnpj, 14, ' ', STR_PAD_RIGHT );
        $sContaCorrente .= $iTipoCredor;
        $sContaCorrente .= str_repeat(0, 12);

        break;

      case 103:

        $iRecurso = ArquivoConfiguracaoTCEAC::getInstancia()->getRecursoPorCodigo( $oContaCorrenteDetalhe->getRecurso()->getCodigo());

        if (empty($iRecurso)) {
          throw new Exception("Código do recurso não encontrado para o recurso {$oContaCorrenteDetalhe->getRecurso()->getCodigo()}.");
        }

        $sContaCorrente .= str_pad( $iRecurso, 3, 0, STR_PAD_LEFT );
        break;

      case 104:

        $oCgm = $oContaCorrenteDetalhe->getCredor();

        $sCpfCnpj    = '';
        $iTipoCredor = 1;

        if ($oCgm->isFisico()) {

          $sCpfCnpj = $oCgm->getCpf();
        } else {

          $sCpfCnpj    = $oCgm->getCnpj();
          $iTipoCredor = 2;
        }

        $sContaCorrente .= str_pad( $sCpfCnpj, 14, ' ', STR_PAD_RIGHT );
        $sContaCorrente .= $iTipoCredor;

        break;

      case 105:

        $oContaBancaria = $oContaCorrenteDetalhe->getContaBancaria();

        $sContaCorrente .= str_pad( $oContaBancaria->getCodigoBanco(), 6, ' ', STR_PAD_RIGHT );
        $sContaCorrente .= str_pad( $oContaBancaria->getNumeroAgencia() . $oContaBancaria->getDVAgencia(), 6, ' ', STR_PAD_RIGHT );
        $sContaCorrente .= str_pad( $oContaBancaria->getNumeroConta() . $oContaBancaria->getDVConta(), 12, ' ', STR_PAD_RIGHT );

        $iTipoConta = ArquivoConfiguracaoTCEAC::getInstancia()->getTipoContaPorCodigo($oContaBancaria->getTipoConta());

        if (empty($iTipoConta)) {
          throw new Exception("Tipo de Conta não encontrado para a conta bancaria {$oContaBancaria->getNumeroConta()}.");
        }

        $sContaCorrente .= $iTipoConta;

        $sNumeroDocumento = '';
        $iTipoDocumento   = 3;

        if ($oLancamento->slip == 't') {

          $iTipoDocumento = 1;

          if (!empty($oLancamento->e91_cheque)) {

            $iTipoDocumento   = 2;
            $sNumeroDocumento = $oLancamento->e91_cheque;
          } else {
            $sNumeroDocumento = $oLancamento->c84_slip;
          }

        } else if ($oLancamento->planilha == 't') {

          if (!empty($oLancamento->k81_operbanco)) {
            $sNumeroDocumento = $oLancamento->k81_operbanco;
          } else {
            $sNumeroDocumento = $oLancamento->k81_codpla;
          }
        } else if ($oLancamento->ordempagamento == 't') {

          $iTipoDocumento = 1;

          if (!empty($oLancamento->cheque_ordem)) {

            $iTipoDocumento   = 2;
            $sNumeroDocumento = $oLancamento->cheque_ordem;
          } else {
            $sNumeroDocumento = $oLancamento->k12_codord;
          }
        } else if (!empty($oLancamento->numpre)) {

          $sNumeroDocumento = $oLancamento->numpre;
        } else {

          throw new Exception("Número do documento não encontrado para o lançamento {$oLancamento->c69_codlan}.");
        }

        $sContaCorrente .= str_pad( $sNumeroDocumento, 15, ' ', STR_PAD_RIGHT );
        $sContaCorrente .= $iTipoDocumento;

        break;

      case 106:

        $oDotacao    = $oContaCorrenteDetalhe->getDotacao();
        $oEmpenho    = $oContaCorrenteDetalhe->getEmpenho();
        $sEstrutural = $oContaCorrenteDetalhe->getEstrutural();

        $sContaCorrente .= str_pad($oDotacao->getFuncao(), 2, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oDotacao->getSubFuncao(), 3, 0, STR_PAD_LEFT);
        $sContaCorrente .= substr($sEstrutural, 1, 1);
        $sContaCorrente .= substr($sEstrutural, 2, 1);
        $sContaCorrente .= substr($sEstrutural, 3, 2);
        $sContaCorrente .= substr($sEstrutural, 5, 2);

        $iRecurso = ArquivoConfiguracaoTCEAC::getInstancia()->getRecursoPorCodigo( $oDotacao->getDadosRecurso()->getCodigo());

        if (empty($iRecurso)) {
          throw new Exception("Código do recurso não encontrado para o recurso {$oDotacao->getDadosRecurso()->getCodigo()}.");
        }

        $sContaCorrente .= str_pad( $iRecurso, 3, 0, STR_PAD_LEFT );
        $sContaCorrente .= str_pad($oEmpenho->getAnoUso(), 4, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oEmpenho->getCodigo(), 12, 0, STR_PAD_LEFT);

        break;

      case 107:

        $oEmpenho = $oContaCorrenteDetalhe->getEmpenho();
        $oAcordo  = $oContaCorrenteDetalhe->getAcordo();

        $sContaCorrente .= str_pad($oAcordo->getNumero(), 12, ' ', STR_PAD_RIGHT);
        $sContaCorrente .= str_pad($oAcordo->getAno(), 4, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oEmpenho->getAnoUso(), 4, 0, STR_PAD_LEFT);
        $sContaCorrente .= str_pad($oEmpenho->getCodigo(), 12, 0, STR_PAD_LEFT);

        break;

      case 108;

        $oAcordo = $oContaCorrenteDetalhe->getAcordo();

        $sContaCorrente .= str_pad($oAcordo->getNumero(), 12, ' ', STR_PAD_RIGHT);
        $sContaCorrente .= str_pad($oAcordo->getAno(), 4, 4, STR_PAD_LEFT);

        break;
    }

    return $sContaCorrente;
  }

  /**
   * Gera o arquivo XML
   */
  private function gerarArquivo() {

    $aContas = ArquivoConfiguracaoTCEAC::getInstancia()->getContaCorrente();

    if (empty($aContas)) {
      throw new Exception("Nenhuma conta corrente encontrada no arquivo de configuração.");
    }

    /**
     * Busca os lançamentos
     */
    $sWhere  = " c02_instit in(" . implode(',', $this->aInstituicoes) . ")                                    \n";
    $sWhere .= " and e81_cancelado is null                                                                    \n";
    $sWhere .= " and (empageconfche.e91_codcheque is null or empageconfche.e91_ativo is true)                 \n";
    $sWhere .= " and c69_data between '{$this->oDataInicial->getDate()}' and '{$this->oDataFinal->getDate()}' \n";
    $oDaoConlancamval = new cl_conlancamval();
    $aQuerys = array("D" => "c69_debito", "C" => "c69_credito");

    $aSqlLancamentos = '';

    foreach ($aQuerys as $sTipo => $sCampo) {

      $sCampos  = " contacorrentedetalhe.*,";
      $sCampos  .= " c69_sequen, c69_codlan, c69_data, c69_valor, {$sCampo} as c69_conta, '{$sTipo}' as tipo, conlancaminstit.c02_instit, c75_numemp,  \n";
      $sCampos .= " conplano.c60_estrut, conplano.c60_identificadorfinanceiro,                                              \n";
      $sCampos .= " case when c115_sequencial is null then false else true end as estorno,                                  \n";
      $sCampos .= " conlancamslip.c84_conlancam is not null as slip, conlancamslip.c84_slip, empageconfche.e91_cheque,      \n";
      $sCampos .= " placaixarec.k81_seqpla is not null as planilha, placaixarec.k81_operbanco, placaixarec.k81_codpla,      \n";
      $sCampos .= " (select k12_numpre                                                                                      \n";
      $sCampos .= "    from cornump                                                                                         \n";
      $sCampos .= "   where cornump.k12_id = corrente.k12_id                                                                \n";
      $sCampos .= "     and cornump.k12_data = corrente.k12_data                                                            \n";
      $sCampos .= "     and cornump.k12_autent = corrente.k12_autent limit 1) as numpre,                                    \n";
      $sCampos .= " coremp.k12_id is not null as ordempagamento, coremp.k12_cheque as cheque_ordem, coremp.k12_codord \n";
      $aSqlLancamentos [] = $oDaoConlancamval->sql_query_contacorrentedetalhe_tce($sCampos, $sTipo, '', $sWhere);
    }

    $sSqlLancamentos = implode(" union all ", $aSqlLancamentos);
    $sSqlLancamentos .= " order by c69_data, c69_sequen";
    $rsLancamentos   = $oDaoConlancamval->sql_record($sSqlLancamentos);

    if ($oDaoConlancamval->erro_status == '0') {
      throw new Exception("Erro ao buscar os dados dos lançamentos.");
    }

    if (!pg_num_rows($rsLancamentos)) {
      throw new Exception("O Período informado não possui lançamentos contábeis.");
    }

    $oXml   = new DOMDocument( "1.0", "utf-8" );
    $oXml->formatOutput = true;
    $oTagLista = $oXml->createElement("lista");

    /**
     * Percorre os lançamentos montando o XML
     */
    for ($iRow = 0; $iRow < pg_num_rows($rsLancamentos); $iRow++) {

      $oTagPartida = $oXml->createElement("partida");

      $oLancamento           = db_utils::fieldsMemory($rsLancamentos, $iRow);
      $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();

      if (!empty($oLancamento->c19_orctiporec)) {
        $oContaCorrenteDetalhe->setRecurso(RecursoRepository::getRecursoPorCodigo($oLancamento->c19_orctiporec));
      }

      if (!empty($oLancamento->c19_estrutural)) {
        $oContaCorrenteDetalhe->setEstrutural($oLancamento->c19_estrutural);
      }

      if (!empty($oLancamento->c19_orcdotacao) && !empty($oLancamento->c19_orcdotacaoanousu)) {
        $oContaCorrenteDetalhe->setDotacao(DotacaoRepository::getDotacaoPorCodigoAno($oLancamento->c19_orcdotacao, $oLancamento->c19_orcdotacaoanousu));
      }

      if (!empty($oLancamento->c19_numemp)) {
        $oContaCorrenteDetalhe->setEmpenho(EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($oLancamento->c19_numemp));
      }

      if (!empty($oLancamento->c19_contabancaria)) {
        $oContaCorrenteDetalhe->setContaBancaria(new ContaBancaria($oLancamento->c19_contabancaria));
      }

      if (!empty($oLancamento->c19_acordo)) {
        $oContaCorrenteDetalhe->setAcordo(AcordoRepository::getByCodigo($oLancamento->c19_acordo));
      }

      if (!empty($oLancamento->c19_numcgm)) {
        $oContaCorrenteDetalhe->setCredor(CgmFactory::getInstanceByCgm($oLancamento->c19_numcgm));
      }

      $sPlanoConta = ArquivoConfiguracaoTCEAC::getInstancia()->getPlanoContaPorCodigo($oLancamento->c60_estrut);

      if (empty($sPlanoConta) || !preg_match("/^(\d)(\d)(\d)(\d)(\d)(\d{2})(\d{2})$/", $sPlanoConta)) {
        throw new Exception("Código estrutural inválido para a conta {$oLancamento->c60_estrut}.");
      }

      /**
       * Seta a contacontabil
       */
      $oTagCondigoContaContabil = $oXml->createElement("codigo", preg_replace("/(\d)(\d)(\d)(\d)(\d)(\d{2})(\d{2})/", "$1.$2.$3.$4.$5.$6.$7", $sPlanoConta) );
      $oTagContaContabil        = $oXml->createElement("contaContabil");
      $oTagContaContabil->appendChild($oTagCondigoContaContabil);

      /**
       * Seta os dados da Conta Corrente
       */
      $lGerarContaCorrente = !empty($oLancamento->c19_sequencial);

      $oTagContaCorrente = $oXml->createElement("conteudoContaCorrente", $this->getLinhaContaCorrente($oContaCorrenteDetalhe, $oLancamento));

      /**
       * Seta o Tipo de conta corrente
       */
      if ($lGerarContaCorrente) {

        $oTagNumeroTipoConta = $oXml->createElement("numero", ArquivoConfiguracaoTCEAC::getInstancia()->getContaCorrentePorCodigo($oLancamento->c19_contacorrente));
        $oTagTipoConta       = $oXml->createElement("tipoDeContaCorrente");
        $oTagTipoConta->appendChild($oTagNumeroTipoConta);
      }

      $oTagNatureza = $oXml->createElement("natureza", $oLancamento->tipo);

      $oTagIdentificadorFinanceiro = null;

      /**
       * Seta o Identificador Financeiro
       */
      if ( in_array($oLancamento->c60_identificadorfinanceiro, array("F", "P"))) {
        $oTagIdentificadorFinanceiro = $oXml->createElement("indicadorSuperavitFinanceiro", $oLancamento->c60_identificadorfinanceiro);
      }

      /**
       * Seta a Tag Lancamento
       */
      $oTagLancamento       = $oXml->createElement("lancamento");
      $oTagNumeroLancamento = $oXml->createElement("numero", $oLancamento->c69_codlan);
      $oTagTipoLancamento   = $oXml->createElement("tipoDeLancamento", ($oLancamento->estorno == 't' ? "ESTORNO" : "ORDINARIO"));

      $oTagLancamento->appendChild($oTagTipoLancamento);
      $oTagLancamento->appendChild($oTagNumeroLancamento);

      /**
       * Seta o Valor
       */
      $oTagValor = $oXml->createElement("valor", $oLancamento->c69_valor);

      /**
       * Monta o registro no xml
       */
      $oTagPartida->appendChild($oTagContaContabil);
      $oTagPartida->appendChild($oTagContaCorrente);

      if ($lGerarContaCorrente) {
        $oTagPartida->appendChild($oTagTipoConta);
      }

      $oTagPartida->appendChild($oTagNatureza);
      if (!empty($oTagIdentificadorFinanceiro)) {
        $oTagPartida->appendChild($oTagIdentificadorFinanceiro);
      }
      $oTagPartida->appendChild($oTagLancamento);
      $oTagPartida->appendChild($oTagValor);

      $oTagLista->appendChild( $oTagPartida );
    }

    $oXml->appendChild( $oTagLista );

    $this->validarXML($oXml, 'config/tce/AC/schema-partida.xsd');
    $this->sArquivo = $oXml->saveXML();
  }

  /**
   * @return text
   */
  public function getArquivo() {
    return $this->sArquivo;
  }

}
