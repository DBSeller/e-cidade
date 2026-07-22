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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoI as Relatorio;

/**
 * Class AnexoI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout
 */
class AnexoI
{

    /**
     * @var \PDFDocument
     */
    protected $oPdf;

    /**
     * @var \stdClass[]
     */
    protected $aLinhas;

    /**
     * @var integer
     */
    protected $iAno;

    /**
     * @var \sInstituicao
     */
    protected $sInstituicao;

    /**
     * @var \DBDate
     */
    protected $oDataInicial;

    /**
     * @var \DBDate
     */
    protected $oDataFinal;

    /**
     * @var \Periodo
     */
    protected $oPeriodo;
    protected $relatorio;

    const COLUNA1DEFAULT = 100;
    const COLUNA1PEQUENA = 27;
    const COLUNA2DEFAULT = 30;
    const COLUNA2PEQUENA = 15;
    const COLUNA2MEDIA = 45;
    const COLUNA2GRANDE = 90;
    const COLUNA1GRANDE = 145;
    const COLUNA3DEFAULT = 50;
    const COLUNA3PEQUENA = 25;
    const COLUNA3MEDIA = 37.5;
    const COLUNA3GRANDE = 100;

    const LINHARECEITAINICIO = 1;
    const LINHARECEITAFIM = 79;
    const LINHADESPESAINICIO = 80;
    const LINHADEPSESAFIM = 102;
    const LINHARECEITAINTRAINICIO = 103;
    const LINHARECEITAINTRAFIM = 165;
    const LINHADESPESAINTRAINICIO = 166;
    const LINHADESPESAINTRAFIM = 174;
    const LIMITELINHA = 277;

    /**
     * @param $iAno
     */
    public function setAno($iAno)
    {
        $this->iAno = $iAno;
    }

    protected $lREstoPagar = false;

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->iAno;
    }

    /**
     * @param $aLinhas
     */
    public function setLinhas($aLinhas)
    {
        $this->aLinhas = $aLinhas;
    }

    /**
     * @return \stdClass[]
     */
    public function getLinhas()
    {
        return $this->aLinhas;
    }

    /**
     * AnexoI constructor.
     *
     * @param integer $iAno
     * @param \Periodo $oPeriodo
     * @param \Instituicao $oInstituicao
     */
    public function __construct($iAno, \Periodo $oPeriodo, $sInstituicao)
    {

        $this->iAno = $iAno;
        $this->oPeriodo = $oPeriodo;
        $this->sInstituicao = $sInstituicao;

        $iAux = static::COLUNA3PEQUENA + 2;
        $this->aLinhaDespesa = array(
            static::COLUNA3DEFAULT,
            static::COLUNA3PEQUENA,
            static::COLUNA3PEQUENA,
            static::COLUNA3PEQUENA,
            static::COLUNA3PEQUENA,
            static::COLUNA3PEQUENA,
            static::COLUNA3PEQUENA,
            static::COLUNA3PEQUENA,
            static::COLUNA3PEQUENA,
            $iAux,
            static::COLUNA3PEQUENA
        );

        $this->aLinhaReceita = array(
            static::COLUNA1DEFAULT,
            static::COLUNA2DEFAULT,
            static::COLUNA2DEFAULT,
            static::COLUNA2DEFAULT,
            static::COLUNA2PEQUENA,
            static::COLUNA2DEFAULT,
            static::COLUNA2PEQUENA,
            static::COLUNA1PEQUENA
        );
    }

    /**
     *
     * Processa as informações que serão impressas no relatório
     */
    protected function processar()
    {
        $this->relatorio = new Relatorio($this->iAno, Relatorio::CODIGO_RELATORIO, $this->oPeriodo->getCodigo());
        $this->relatorio->setDataInicial($this->relatorio->getDataInicialPeriodo());
        $this->relatorio->setInstituicoes($this->sInstituicao);
        $this->aLinhas = $this->relatorio->getLinhas();
        $this->oDataFinal = $this->relatorio->getDataFinal();
        $this->oDataInicial = $this->relatorio->getDataInicialPeriodo();
        $this->ajustaDespesas();
    }

    /**
     * Emite o relatório em PDF
     */
    public function emitir()
    {

        $this->processar();

        $this->oPdf = new \PDFDocument("L");

        $oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();
        $sMesInicio = mb_strtoupper(\DBDate::getMesExtenso($this->oDataInicial->getMes()));
        $sMesFim = mb_strtoupper(\DBDate::getMesExtenso($this->oDataFinal->getMes()));

        $aInstituicoes = explode(",", $this->sInstituicao);
        $this->oPdf->addHeaderDescription('');

        if (count($aInstituicoes) == 1) {
            $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);
            $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

            if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
                $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
            }
        } else {
            $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));
        }

        $this->oPdf->addHeaderDescription('RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA');
        $this->oPdf->addHeaderDescription('BALANÇO ORÇAMENTÁRIO');
        $this->oPdf->addHeaderDescription('ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL');
        $legendaHeader = "JANEIRO À {$sMesFim} DE {$this->getAno()} - BIMESTRE {$sMesInicio} - {$sMesFim}";
        $this->oPdf->addHeaderDescription($legendaHeader);

        $this->oPdf->open();
        $this->oPdf->setFillColor(235);

        $this->imprimeCabecalhoReceita();
        $this->imprimeReceitas();
        $this->imprimeCabecalhoDespesa();
        $this->imprimeDespesas();
        $this->imprimeCabecalhoReceita(true);
        $this->imprimeReceitas(true);
        $this->imprimeCabecalhoDespesa(true);
        $this->imprimeDespesas(true);
        $this->imprimeLinhaFinal();
        $this->oPdf->ln();
        $classinatura = new \cl_assinatura;
        $oRelatorio = new \relatorioContabil($this->relatorio->getCodigoRelatorio(), false);
        $this->adicionarLegendas();
        $oRelatorio->getNotaExplicativa($this->oPdf, $this->oPeriodo->getCodigo());
        $this->oPdf->cell(0, 25, " ", "", 1, "");
        assinaturas($this->oPdf, $classinatura, 'LRF');

        $this->oPdf->showPDF("AnexoI");
    }

    protected function ajustaDespesas()
    {

        if ($this->oPeriodo->getCodigo() == 11) {
            $this->lREstoPagar = true;
        }

        // Verifica se o Bimestre é o ultimo do ano, caso seja, é exibida a coluna de restos a pagar
        if ($this->lREstoPagar) {
            $this->aLinhaDespesa[3] = $this->aLinhaDespesa[3] - ($this->aLinhaDespesa[3] / 4);
            $this->aLinhaDespesa[4] = $this->aLinhaDespesa[3];
            $this->aLinhaDespesa[6] = $this->aLinhaDespesa[3];
            $this->aLinhaDespesa[7] = $this->aLinhaDespesa[3];
        }
    }

    /**
     * Imprime o cabeçalho da Receita
     * @param bool $lIntra
     */
    public function imprimeCabecalhoReceita($lIntra = false)
    {

        $this->imprimeLinhaFinal();
        $this->oPdf->addPage();
        $sDescricao = "RECEITAS";
        if ($lIntra) {
            $sDescricao = "RECEITAS INTRA-ORÇAMENTÁRIAS";
        }

        $this->oPdf->cell(0, 2, "", "", 1, "");
        $this->oPdf->setFontSize(5);
        $this->oPdf->cell(
            static::COLUNA1DEFAULT,
            3,
            "RREO - Anexo 1 (LRF, Art. 52, inciso I, alíneas 'a' e 'b' do inciso II e §1º)",
            "",
            0,
            "L"
        );
        $this->oPdf->cell((static::COLUNA2GRANDE * 2) - 3, 3, "Em Reais", "", 1, "R");
        $this->oPdf->setBold(1);
        $this->oPdf->setFontSize(5);
        $this->oPdf->setUnderline(1);
        $this->oPdf->cell(static::COLUNA1DEFAULT, 8, $sDescricao, "TRB", 0, "C", 1);
        $this->oPdf->setUnderline(0);
        $this->oPdf->cell(static::COLUNA2DEFAULT, 8, 'PREVISÃO INICIAL', "TRB", 0, "C", 1);

        $iX = $this->oPdf->getX();
        $iYInicial = $this->oPdf->getY();

        $this->oPdf->MultiCell(static::COLUNA2DEFAULT, 4, "PREVISÃO ATUALIZADA \n(a)", "TRB", 'C', 1);
        $this->oPdf->SetY($iYInicial);
        $this->oPdf->SetX($iX + static::COLUNA2DEFAULT);
        $iX = $this->oPdf->getX();

        $this->oPdf->MultiCell(static::COLUNA2GRANDE, 4, "RECEITAS REALIZADAS", "TRB", 'C', 1);
        $this->oPdf->SetX($iX);
        $iY = $this->oPdf->getY();
        $iX = $this->oPdf->getX();

        $this->oPdf->MultiCell(static::COLUNA2DEFAULT, 2, "No Bimestre \n (b)", "TRB", 'C', 1);
        $this->oPdf->SetY($iY);
        $this->oPdf->SetX($iX + static::COLUNA2DEFAULT);
        $iX = $this->oPdf->getX();

        $this->oPdf->MultiCell(static::COLUNA2PEQUENA, 2, "% \n (b/a)", "TRB", 'C', 1);
        $this->oPdf->SetY($iY);
        $this->oPdf->SetX($iX + static::COLUNA2PEQUENA);
        $iX = $this->oPdf->getX();

        $this->oPdf->MultiCell(static::COLUNA2DEFAULT, 2, "Até o Bimestre \n (c)", "TRB", 'C', 1);
        $this->oPdf->SetY($iY);
        $this->oPdf->SetX($iX + static::COLUNA2DEFAULT);
        $iX = $this->oPdf->getX();

        $this->oPdf->MultiCell(static::COLUNA2PEQUENA, 2, "% \n (c/a)", "TRB", 'C', 1);
        $this->oPdf->SetY($iYInicial);
        $this->oPdf->SetX($iX + static::COLUNA2PEQUENA);
        $iX = $this->oPdf->getX();

        $this->oPdf->cell(static::COLUNA1PEQUENA, 4, "SALDO", "T", 0, "C", 1);

        $this->oPdf->SetY($iY);
        $this->oPdf->SetX($iX);

        $this->oPdf->cell(static::COLUNA1PEQUENA, 4, "(a-c)", "B", 1, "C", 1);

        $this->oPdf->setBold(0);
    }

    /**
     * Imprime o cabeçalho da despesa
     * @param bool $lIntra
     */
    public function imprimeCabecalhoDespesa($lIntra = false)
    {

        $this->imprimeLinhaFinal();
        $this->oPdf->addPage();
        $sDescricao = "DESPESAS";

        if ($lIntra) {
            $sDescricao = "DESPESAS INTRA-ORÇAMENTÁRIAS";
        }

        $this->oPdf->cell(0, 2, "", "", 1, "");
        $this->oPdf->setFontSize(5);
        $this->oPdf->setBold(1);
        $this->oPdf->cell(
            static::COLUNA1DEFAULT,
            3,
            "RREO - Anexo 1 (LRF, Art. 52, inciso I, alíneas 'a' e 'b' do inciso II e §1º)",
            "",
            0,
            "L"
        );
        $this->oPdf->cell((static::COLUNA2GRANDE * 2) - 3, 3, "Em Reais", "", 1, "R");
        $this->oPdf->setUnderline(1);
        $this->oPdf->cell($this->aLinhaDespesa[0], 9, $sDescricao, "TRB", 0, "C", 1);
        $this->oPdf->setUnderline(0);
        $iX = $this->oPdf->getX();
        $iYInicial = $this->oPdf->getY();
        $this->oPdf->MultiCell($this->aLinhaDespesa[1], 3, "DOTAÇÃO \nINICIAL \n(d)", "TRB", 'C', 1);
        $this->oPdf->SetY($iYInicial);
        $this->oPdf->SetX($iX + $this->aLinhaDespesa[1]);

        $iX = $this->oPdf->getX();
        $this->oPdf->MultiCell($this->aLinhaDespesa[2], 3, "DOTAÇÃO \nATUALIZADA \n(e)", "TRB", 'C', 1);
        $this->oPdf->SetY($iYInicial);
        $iX += $this->aLinhaDespesa[2];
        $this->oPdf->SetX($iX);

        $this->oPdf->cell(
            $this->aLinhaDespesa[3] + $this->aLinhaDespesa[4],
            3,
            "DESPESAS EMPENHADAS",
            "TRB",
            1,
            "C",
            1
        );
        $iY = $this->oPdf->getY();
        $this->oPdf->SetX($iX);

        $iX += $this->aLinhaDespesa[3];
        $this->oPdf->MultiCell($this->aLinhaDespesa[3], 6, "NO BIMESTRE", "TRB", 'C', 1);
        $this->oPdf->SetY($iY);

        $this->oPdf->SetX($iX);
        $this->oPdf->MultiCell($this->aLinhaDespesa[4], 2, "ATÉ O \n BIMESTRE\n (f)", "TRB", 'C', 1);
        $iX += $this->aLinhaDespesa[4];
        $this->oPdf->SetY($iYInicial);

        $this->oPdf->SetX($iX);
        $this->oPdf->MultiCell($this->aLinhaDespesa[5], 4.5, "SALDO\n(g) = (e-f)", "TRB", 'C', 1);
        $iX += $this->aLinhaDespesa[5];
        $this->oPdf->SetY($iYInicial);

        $this->oPdf->SetX($iX);
        $this->oPdf->cell(
            $this->aLinhaDespesa[6] + $this->aLinhaDespesa[7],
            3,
            "DESPESAS LIQUIDADAS",
            "TRB",
            1,
            "C",
            1
        );
        $this->oPdf->SetX($iX);
        $iY = $this->oPdf->getY();
        $iX += $this->aLinhaDespesa[6];

        $this->oPdf->MultiCell($this->aLinhaDespesa[6], 6, "NO BIMESTRE", "TRB", 'C', 1);

        $this->oPdf->SetY($iY);
        $this->oPdf->SetX($iX);
        $this->oPdf->MultiCell($this->aLinhaDespesa[7], 2, "ATÉ O \n BIMESTRE\n (h)", "TRB", 'C', 1);
        $iX += $this->aLinhaDespesa[7];


        $this->oPdf->SetY($iYInicial);
        $this->oPdf->SetX($iX);
        $this->oPdf->MultiCell($this->aLinhaDespesa[8], 4.5, "SALDO\n(i) = (e-h)", "TRB", 'C', 1);
        $iX += $this->aLinhaDespesa[8];


        $this->oPdf->SetY($iYInicial);
        $this->oPdf->SetX($iX);
        $this->oPdf->MultiCell(
            $this->aLinhaDespesa[9],
            2.25,
            "DESPESAS\n PAGAS ATÉ O \n BIMESTRE\n (j)",
            "TRB",
            'C',
            1
        );
        $iX += $this->aLinhaDespesa[9];

        if ($this->lREstoPagar) {
            $this->oPdf->SetY($iYInicial);
            $this->oPdf->SetX($iX);
            $this->oPdf->MultiCell(
                $this->aLinhaDespesa[10],
                2.25,
                "INSCRITAS EM\n RESTOS A PAGAR NÃO \n PROCESSADOS\n (k)",
                "TB",
                'C',
                1
            );
        }

        $this->oPdf->setBold(0);
    }

    /**
     * Imprime as linhas da Receita
     * @param bool $lIntra
     */
    protected function imprimeReceitas($lIntra = false)
    {

        $iInicio = static::LINHARECEITAINICIO;
        $iFim = static::LINHARECEITAFIM;

        if ($lIntra) {
            $iInicio = static::LINHARECEITAINTRAINICIO;
            $iFim = static::LINHARECEITAINTRAFIM;
        }

        for ($i = $iInicio; $i <= $iFim; $i++) {
            if (($i - ($iInicio - 1)) % 35 == 0) {
                $this->imprimeCabecalhoReceita($lIntra);
            }
            $this->imprimeValoresReceitas($this->aLinhas[$i]);
        }
    }

    /**
     * Imprime as linhas da despesa
     * @param bool $lIntra
     */
    protected function imprimeDespesas($lIntra = false)
    {

        $iInicio = static::LINHADESPESAINICIO;
        $iFim = static::LINHADEPSESAFIM;

        if ($lIntra) {
            $iInicio = static::LINHADESPESAINTRAINICIO;
            $iFim = static::LINHADESPESAINTRAFIM;
        }

        for ($i = $iInicio; $i <= $iFim; $i++) {
            if (($i - ($iInicio - 1)) % 35 == 0) {
                $this->imprimeCabecalhoDespesa($lIntra);
            }
            $this->imprimeValoresDespesas($this->aLinhas[$i]);
        }
    }

    protected function imprimeLinhaFinal()
    {
        $this->oPdf->cell(static::LIMITELINHA, 1, "", "T", 0, "R");
    }

    /**
     * @param      $oLinha
     * @param bool $lIntra
     */
    protected function imprimeValoresReceitas($oLinha)
    {

        $sLine = "";

        if ($oLinha->totalizar) {
            $this->oPdf->setBold(1);
        }
        if ($oLinha->nivel == 1) {
            $sLine = "T";
        }

        $fPorcentagem1 = '-';
        $fPorcentagem2 = '-';

        if ($oLinha->previni === '-') {
            $iSaldo = '-';
        } else {
            $fPorcentagem1 = db_formatar(0, 'f');
            $fPorcentagem2 = db_formatar(0, 'f');

            if (!empty($oLinha->recnobim) && !empty($oLinha->prevatu)) {
                $fPorcentagem1 = db_formatar(((float)$oLinha->recnobim / (float)$oLinha->prevatu) * 100, "f");
                $fPorcentagem2 = db_formatar(((float)$oLinha->recatebim / (float)$oLinha->prevatu) * 100, "f");
            }
            $iSaldo = db_formatar($oLinha->prevatu - $oLinha->recatebim, "f");
        }
        $this->formataValor($oLinha);

        if ($oLinha->ordem == $this->relatorio->getLinhaDeficitVI()) {
            if ($oLinha->recatebim == 0) {
                $oLinha->recatebim = db_formatar($oLinha->recatebim, 'f');
            }
        }

        $this->oPdf->cell(
            $this->aLinhaReceita[0],
            4,
            \relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao,
            $sLine . "R",
            0,
            "L"
        );
        $this->oPdf->cell($this->aLinhaReceita[1], 4, $oLinha->previni, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaReceita[2], 4, $oLinha->prevatu, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaReceita[3], 4, $oLinha->recnobim, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaReceita[4], 4, $fPorcentagem1, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaReceita[5], 4, $oLinha->recatebim, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaReceita[6], 4, $fPorcentagem2, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaReceita[7], 4, $iSaldo, $sLine, 1, "R");
        $this->oPdf->setBold(0);
    }

    protected function imprimeValoresDespesas($oLinha)
    {
        $sLine = "";

        if ($oLinha->totalizar) {
            $this->oPdf->setBold(1);
        }
        if ($oLinha->nivel == 1) {
            $sLine = "T";
            $this->oPdf->setBold(1);
        }

        if ($oLinha->dotini === '-' || $oLinha->ordem == 103) {
            $saldo1 = '-';
            $saldo2 = '-';
        } else {
            $saldo1 = db_formatar($oLinha->dotatu - $oLinha->empenhado_atebim, "f");
            $saldo2 = db_formatar($oLinha->dotatu - $oLinha->liquidado_atebim, "f");
        }
        if ($oLinha->ordem == 103) {
            $valor101 = str_replace(',', '.', str_replace('.', '', $this->aLinhas[101]->empenhado_atebim));
            $valor102 = 0;
            if ($this->aLinhas[102]->empenhado_atebim != '-') {
                $valor102 = str_replace(',', '.', str_replace('.', '', $this->aLinhas[102]->empenhado_atebim));
            }
            $oLinha->empenhado_atebim = ($valor101 + $valor102);
        }
        $this->formataValor($oLinha);

        if ($oLinha->ordem == $this->relatorio->getLinhaSuperavitXIII()) {
            if ($oLinha->liquidado_atebim == 0) {
                $oLinha->liquidado_atebim = db_formatar($oLinha->liquidado_atebim, 'f');
            }
        }

        $this->oPdf->cell(
            $this->aLinhaDespesa[0],
            4,
            \relatorioContabil::getIdentacao($oLinha->nivel) . $oLinha->descricao,
            $sLine . "R",
            0,
            "L"
        );
        $this->oPdf->cell($this->aLinhaDespesa[1], 4, $oLinha->dotini, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaDespesa[2], 4, $oLinha->dotatu, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaDespesa[3], 4, $oLinha->empenhado_nobim, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaDespesa[4], 4, $oLinha->empenhado_atebim, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaDespesa[5], 4, $saldo1, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaDespesa[6], 4, $oLinha->liquidado_nobim, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaDespesa[7], 4, $oLinha->liquidado_atebim, $sLine . "R", 0, "R");
        $this->oPdf->cell($this->aLinhaDespesa[8], 4, $saldo2, $sLine . "R", 0, "R");
        if ($this->lREstoPagar) {
            $this->oPdf->cell($this->aLinhaDespesa[9], 4, $oLinha->desppag, $sLine . "R", 0, "R");
            $this->oPdf->cell($this->aLinhaDespesa[10], 4, $oLinha->rp_apagar, $sLine, 1, "R");
        } else {
            $this->oPdf->cell($this->aLinhaDespesa[9], 4, $oLinha->desppag, $sLine, 1, "R");
        }
        $this->oPdf->setBold(0);
    }

    protected function formataValor($oLine)
    {

        $lFormata = true;

        if ($oLine->{$oLine->colunas[0]->o115_nomecoluna} == '-') {
            if (!empty($oLine->{$oLine->colunas[0]->o115_nomecoluna})) {
                $lFormata = false;
            }
        }

        foreach ($oLine->colunas as $oColuna) {
            if ($lFormata) {
                $oLine->{$oColuna->o115_nomecoluna} = db_formatar($oLine->{$oColuna->o115_nomecoluna}, "f");
            }
        }
    }

    protected function adicionarLegendas()
    {
        return;
    }
}
