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

class RelatorioDevolucoes {

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * @var integer
   */
  private $iLarguraPagina;

    /**
     * @var double
     */
    private $dComprimentoCelula;

    /**
   * @var integer
   */
  private $iAlturaLinha;

  /**
   * @var DBDate
   */
  private $oDataInicial;

  /**
   * @var DBDate
   */
  private $oDataFinal;

  /**
   * @var integer
   */
  private $iCgm;

  /**
   * @param int $iCgm
   */
  public function setCgm($iCgm) {
    $this->iCgm = $iCgm;
  }

  /**
   * @return int
   */
  public function getCgm() {
    return $this->iCgm;
  }

  /**
   * @param DBDate $oDataInicial
   */
  public function setDataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * @return DBDate
   */
  public function getDataInicial() {
    return $this->oDataInicial;
  }

  /**
   * @param DBDate $oDataFinal
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   * @return DBDate
   */
  public function getDataFinal() {
    return $this->oDataFinal;
  }

  /**
   * @return bool|resource
   * @throws DBException
   */
  private function getDados() {

    $oDaoAbatimentoUtilizacao = new cl_abatimento();

      $sCampos = implode(', ', array(
      "z01_numcgm         as numero_cgm",
      "recibo.k00_receit  as receita_credito",
      "recibo.k00_numpre  as origem",
      "k157_data          as data_devolucao",
      "z01_nome           as nome_cgm",
      "k157_valor as valor_devolucao",
      "k00_valor as valor_original",
      "coalesce((select k167_data from abatimentocorrecao where k167_abatimento = k125_sequencial order by k167_data desc limit 1), k125_datalanc) as data_correcao",
      "coalesce((select sum(k167_valorcorrigido - k167_valorantigo) from abatimentocorrecao where k167_abatimento = abatimento.k125_sequencial), 0) as valor_corrigido",
      "coalesce((select sum(k167_valorcorrigido - k167_valorantigo) from abatimentocorrecao where k167_abatimento = abatimento.k125_sequencial), 0) as valor_corrigido2",
      "coalesce((select sum(k157_valor) from abatimentoutilizacao where k157_abatimento = abatimento.k125_sequencial), 0) as valor_utilizado"
    ));

    $aWhere = array(
      "k157_tipoutilizacao = '" . CreditoCompensacao::TIPO_UTILIZACAO_DEVOLUCAO . "'"
    );

    if ($this->getCgm()) {
      $aWhere[] = "arrenumcgm.k00_numcgm = {$this->getCgm()}";
    }

    if ($this->getDataInicial()) {
      $aWhere[] = "abatimentoutilizacao.k157_data >= '{$this->getDataInicial()->getDate()}'";
    }

    if ($this->getDataFinal()) {
      $aWhere[] = "abatimentoutilizacao.k157_data <= '{$this->getDataFinal()->getDate()}'";
    }

    $sWhere = implode(' and ', $aWhere);
    $sOrder = 'recibo.k00_numpre, z01_nome, recibo.k00_receit, k157_data';

    $sSqlUtilizacoes = $oDaoAbatimentoUtilizacao->sql_query_utilizacao($sCampos, $sWhere, $sOrder);
    $rsUtilizacoes   = db_query($sSqlUtilizacoes);

    if (!$rsUtilizacoes) {
      throw new DBException("Não foi possível encontrar as devoluções.");
    }

    return $rsUtilizacoes;
  }

  /**
   * Configurar Emissão e Filtros no Cabeçalho do Relatório
   */
  private function configurar() {

    $this->oPdf->Open();
    $this->oPdf->addHeaderDescription("DEVOLUÇÕES DE CRÉDITO");
    $this->oPdf->addHeaderDescription("");

    if ($this->getDataInicial() && $this->getDataFinal()) {
      $this->oPdf->addHeaderDescription(
        "PERÍODO: {$this->getDataInicial()->getDate(DBDate::DATA_PTBR)}" .
        " ATÉ {$this->getDataFinal()->getDate(DBDate::DATA_PTBR)}"
      );
    }

    if ($this->getDataInicial() && !$this->getDataFinal()) {
      $this->oPdf->addHeaderDescription("PERÍODO: {$this->getDataInicial()->getDate(DBDate::DATA_PTBR)}");
    }

    if (!$this->getDataInicial() && $this->getDataFinal()) {
      $this->oPdf->addHeaderDescription("PERÍODO: {$this->getDataFinal()->getDate(DBDate::DATA_PTBR)}");
    }

    if ($this->getCgm()) {
      $this->oPdf->addHeaderDescription("CGM: {$this->getCgm()}");
    }

    $this->oPdf->setAutoNewLineMulticell(true);
    $this->oPdf->SetFillColor(235);
    $this->oPdf->setFontFamily("arial");
    $this->oPdf->SetFontSize(6);

    $this->iAlturaLinha = 4;
    $this->iLarguraPagina = $this->oPdf->getAvailWidth() - $this->oPdf->GetRightMargin();
    $this->dComprimentoCelula = (100/4)*0.01;
  }

  /**
   * Emitor Relatório
   */
  public function emitir() {

    $this->oPdf = new PDFDocument(PDFDocument::PRINT_PORTRAIT);
    $this->configurar();

    $rsRegistros = $this->getDados();
    $iTotalRegistros = pg_numrows($rsRegistros);
    $iUltimoNumpre = null;
    $lTotal = false;
    $aTotal= ['total'=>0, 'total_original' => 0, 'total_corrigido' =>0];
    $abatimentos = [];
    for ($iIndice = 0; $iIndice < $iTotalRegistros; $iIndice++) {

      $oRegistro = db_utils::fieldsMemory($rsRegistros, $iIndice);

      $lPrimeiroRegistro = $iIndice === 0;
      $lQuebraPagina     = $this->oPdf->getAvailHeight() < 30;
      $lMudancaNumpre    = $iUltimoNumpre && $oRegistro->origem !== $iUltimoNumpre;

      if ($lPrimeiroRegistro || $lQuebraPagina || $lMudancaNumpre) {

        if ($lPrimeiroRegistro || $lQuebraPagina) {
          $this->oPdf->AddPage();
        }

        if ($lMudancaNumpre && !$lQuebraPagina) {
          $this->oPdf->ln(6);
        }

        $this->escreverCabecalhoCgm();
        $this->escreverRegistroCgm($oRegistro);
        $this->escreverCabecalhoNumpre();
        $this->escreverRegistroNumpre($oRegistro);
      }
      if(empty($abatimentos[$oRegistro->origem])) {
        $abatimentos[$oRegistro->origem]['valordisponivel'] = $oRegistro->valor_utilizado;
      }

      if (!empty($oRegistro->valor_corrigido)) {
        $nValorCorrigido = ($oRegistro->valor_original + $oRegistro->valor_corrigido);
      } else {
        $nValorCorrigido = $oRegistro->valor_disponivel;
      }

      if ($abatimentos[$oRegistro->origem]['valordisponivel'] >= $nValorCorrigido) {
        $abatimentos[$oRegistro->origem]['valordisponivel'] -= $nValorCorrigido ;
        $oRegistro->valor_utilizado  = $nValorCorrigido;
        $oRegistro->valor_disponivel = 0;
      } elseif ($abatimentos[$oRegistro->origem]['valordisponivel'] > 0) {
        $oRegistro->valor_utilizado  = $abatimentos[$oRegistro->origem]['valordisponivel'];
        $oRegistro->valor_disponivel = $nValorCorrigido - $abatimentos[$oRegistro->origem]['valordisponivel'];
        $abatimentos[$oRegistro->origem]['valordisponivel'] -= $oRegistro->valor_disponivel;
      }

      $aTotal['total'] += $oRegistro->valor_devolucao;
      $aTotal['total_original'] += $oRegistro->valor_original;
      $aTotal['total_corrigido'] += $oRegistro->valor_utilizado;

      $iUltimoNumpre = $oRegistro->origem;
    }

    $this->oPdf->showPDF("RelatorioDevolucoes_" . time());
  }

  /**
   * Escreve o cabeçalho da devolução
   */
  private function escreverCabecalhoNumpre() {

    $this->oPdf->SetFillColor(235);
    $comprimento = $this->iLarguraPagina * $this->dComprimentoCelula;
    $this->oPdf->Cell($comprimento, $this->iAlturaLinha, "NUMPRE", 'LTB', 0, 'C', 1);
    $this->oPdf->Cell($comprimento, $this->iAlturaLinha, "DATA", 'LTB', 0, 'C', 1);
    $this->oPdf->Cell($comprimento*2, $this->iAlturaLinha, "VALOR", 'LTBR', 1, 'C', 1);
    $this->oPdf->SetFillColor(255);
  }

  /**
   * Escreve os dados da devolução
   *
   * @param stdClass $oRegistro
   * @param integer  $lPreencher
   */
  private function escreverRegistroNumpre($oRegistro, $lPreencher = 0) {
      $comprimento = $this->iLarguraPagina * $this->dComprimentoCelula;

    $this->oPdf->Cell(
        $comprimento, $this->iAlturaLinha,
      $oRegistro->origem, 'LB', 0, 'C', $lPreencher
    );
    $this->oPdf->Cell(
        $comprimento, $this->iAlturaLinha,
      db_formatar($oRegistro->data_devolucao,'d'), 'LB', 0, 'C', $lPreencher
    );
    $this->oPdf->Cell(
        $comprimento*2, $this->iAlturaLinha,
        db_formatar($oRegistro->valor_utilizado, 'f'), 'LBR', 1, 'R', 0
    );
  }

    /**
     * Escreve os dados da devolução
     *
     * @param stdClass $oRegistro
     * @param integer  $lPreencher
     */
    private function escreverRegistroTotal($aTotal, $lPreencher = 0) {
        $comprimento = $this->iLarguraPagina * $this->dComprimentoCelula;

        $this->oPdf->Cell(
            $comprimento, $this->iAlturaLinha,
            "Total", 'LB', 0, 'C', $lPreencher
        );
        $this->oPdf->Cell(
            $comprimento, $this->iAlturaLinha,
            "", 'B', 0, 'C', $lPreencher
        );
        $this->oPdf->Cell(
            $comprimento*2, $this->iAlturaLinha,
            db_formatar($aTotal['total_corrigido'], 'f'), 'LBR', 1, 'R', 0
        );
    }

  /**
   * Escreve o cabeçalho do CGM
   */
  private function escreverCabecalhoCgm() {

    $this->oPdf->SetFillColor(235);
    $this->oPdf->Cell($this->iLarguraPagina * 0.25, $this->iAlturaLinha, "CGM", 'LTB', 0, 'C', 1);
    $this->oPdf->Cell($this->iLarguraPagina * 0.75, $this->iAlturaLinha, "NOME", 'LTBR', 1, 'C', 1);
    $this->oPdf->SetFillColor(255);
  }

  /**
   *
   * @param stdClass $oRegistro
   * @param integer  $lPreencher
   */
  private function escreverRegistroCgm($oRegistro, $lPreencher = 0) {

    $this->oPdf->Cell(
      $this->iLarguraPagina * 0.25, $this->iAlturaLinha,
      $oRegistro->numero_cgm, 'LB', 0, 'C', $lPreencher
    );
    $this->oPdf->Cell(
      $this->iLarguraPagina * 0.75, $this->iAlturaLinha,
      $oRegistro->nome_cgm, 'LBR', 1, 'C', $lPreencher
    );
  }

}
