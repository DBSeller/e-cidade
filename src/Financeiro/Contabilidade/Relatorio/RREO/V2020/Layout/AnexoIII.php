<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\InterfaceRelatorioLegal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Layout\Layout;

/**
 * Class AnexoXII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\Layout
 */
class AnexoIII extends Layout implements InterfaceRelatorioLegal
{

    /**
     * @var \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\AnexoIII
     */
    protected $oAnexo;

    /**
     * AnexoII constructor.
     */
    public function __construct()
    {
        $this->oPdf = new \PDFDocument("L");
        $this->oPdf->setFillColor(235);
        $this->oPdf->SetAutoPageBreak(false);
    }

    public function header()
    {
        $oPerido = $this->oAnexo->getPeriodo();
        $sMesFim = mb_strtoupper(\DBDate::getMesExtenso($oPerido->getMesFinal()));
        $sMesInicio = mb_strtoupper(\DBDate::getMesExtenso($this->oAnexo->getDataDeinicio()->format("m")));

        $anoDeInicio = $this->oAnexo->getDataDeinicio()->format("Y");
        $oInstituicao = \InstituicaoRepository::getInstituicaoSessao();
        $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

        if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
            $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
        }

        $ano = $this->oAnexo->getAno();
        $this->oPdf->addHeaderDescription('RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTARIA');
        $this->oPdf->addHeaderDescription('DEMONSTRATIVO DA RECEITA CORRENTE LIQUIDA');
        $this->oPdf->addHeaderDescription('ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL');
        $this->oPdf->addHeaderDescription("{$sMesInicio}/{$anoDeInicio} A {$sMesFim}/{$ano}");
        $this->oPdf->open();
        $this->oPdf->AddPage();
    }

    /**
     * Emissao do relatorio
     */
    public function emitir()
    {
        $this->header();
        $this->pageHeader();
        $this->oPdf->SetFont('Arial', '', 6);
        $aLinhas = $this->oAnexo->getDados();
        $this->setColunasDoRelatorio();
        $this->imprimirLinhas($aLinhas);
        $this->oAnexo->getNotaExplicativa($this->oPdf, 178, 280);
        $this->oPdf->ln(15);
        $assinatura = new \cl_assinatura;
        assinaturas($this->oPdf, $assinatura, 'LRF');
        $this->oPdf->showPDF('tmp/anexoiii');
    }

    protected function setColunasDoRelatorio()
    {
        $colunas = [(object)[
            "campo" => "descricao",
            "tamanho" => 60,
            "alinhamento" => "L",
            "label" => "Especificacao",
            "formato" => "",
            "borda" => "R",
            "preenchimento" => 0,
        ]];
        foreach ($this->oAnexo->getMesesParaProcessar() as $mes) {
            $colunas[] = (object)[
                "campo" => $mes["nome_coluna"],
                "tamanho" => 16,
                "alinhamento" => "R",
                "label" => $mes["label"],
                "borda" => "LR",
                "formato" => "moeda",
                "preenchimento" => 0,
            ];
        }
        $colunas[] = (object)[
            "campo" => "total",
            "tamanho" => 16,
            "alinhamento" => "R",
            "label" => "Total Últimos 12 Meses",
            "borda" => "LR",
            "formato" => "moeda",
            "preenchimento" => 0,
        ];
        $colunas[] = (object)[
            "campo" => "previsao_atualizada",
            "tamanho" => 16,
            "alinhamento" => "R",
            "label" => "Previsão Inicial",
            "borda" => "L",
            "formato" => "moeda",
            "preenchimento" => 0,
        ];

        $this->colunas = $colunas;
    }

    public function emitirDadosSimplificado()
    {
    }

    public function pageHeader()
    {
        $alturaColuna = 4;
        $this->oPdf->SetFont('Arial', '', 6);
        $this->oPdf->cell(60, $alturaColuna, "RREO - Anexo III (LRF, Art. 53, inciso I)", "b", 0, "L", 0);
        $this->oPdf->cell(200, $alturaColuna, "R$ 1,00", "b", 1, "R", 0);
        $this->oPdf->cell(60, $alturaColuna, "ESPECIFICAÇÃO", 'RT', 0, "C", 0);
        $this->oPdf->cell(192, $alturaColuna, "EVOLUÇÃO DA RECEITA REALIZADA NOS ÚLTIMOS 12 MESES", 'RTB', 0, "C", 0);
        $this->oPdf->cell(16, $alturaColuna, "TOTAL", 'RT', 0, "C", 0);
        $this->oPdf->cell(15, $alturaColuna, "PREVISAO", 'T', 0, "C", 0);
        $this->oPdf->ln();
        $this->oPdf->cell(60, $alturaColuna, "", 'BR', 0, "C", 0);
        foreach ($this->oAnexo->getMesesParaProcessar() as $mes) {
            $this->oPdf->cell(16, $alturaColuna, $mes["label"], 'RBT', 0, "C", 0);
        }
        $this->oPdf->cell(16, $alturaColuna, "ULT 12 MESES", 'BR', 0, "C", 0);
        $this->oPdf->cell(16, $alturaColuna, "ATUAL EXERC", 'B', 0, "C", 0);
        $this->oPdf->ln();
        $this->oPdf->SetFont('Arial', '', 6);
    }
}
