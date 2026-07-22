<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model;

class BalancoPatrimonialDCASP2019 extends BalancoPatrimonialDCASP2018
{
    /**
     * @var integer
     */
    const CODIGO_RELATORIO = 205;

    public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo)
    {
        parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
        $this->aLinhasTotalizadoras = array(7, 17, 18, 27, 36, 46, 47, 51, 63, 64, 70, 76);
        $this->linhas['balanco_patrimonial']['final'] += 8;
        $this->linhas['contas_compensacao']['inicio'] += 8;
        $this->linhas['contas_compensacao']['final'] += 8;
    }

    public function emitir()
    {

        $this->preparaCabecalhos();

        $this->aDados = $this->getDados();
        $this->processarQuadros();
        $this->aQuadroSuperavitDeficit = $this->getSuperavitDeficit();

        $this->configurarPdf();
        $this->processarFormasDasLinhas(array(60,53,52,63,64,65,70,71,76));

        $quadros = array(
            (object)array(
                "nome" => 'QUADRO PRINCIPAL',
                "coluna" => "ATIVO",
                "quadro" => $this->aQuadroPrincipal
            ),
            (object)array(
                "nome" => "QUADRO DE ATIVOS E PASSIVOS FINANCEIROS E PERMANENTES\n(Lei nº 4.320/1964)",
                "coluna" => "",
                "quadro" => $this->aQuadroAtivosPassivos
            ),
            (object)array(
                "nome" => "QUADRO DE CONTAS DE COMPENSAÇÃO\n(Lei nº 4.320/1964)",
                "coluna" => "",
                "quadro" => $this->aQuadroContasCompensacao
            ),
            (object)array(
                "nome" => "QUADRO DE SUPERÁVIT/DÉFICIT FINANCEIRO\n(Lei nº 4.320/1964)",
                "coluna" => "FONTES DE RECURSOS",
                "quadro" => $this->aQuadroSuperavitDeficit
            )
        );

        foreach ($quadros as $stdQuadro) {
            $this->emitirQuadro($stdQuadro->nome, $stdQuadro->coluna, $stdQuadro->quadro);
        }
        $this->escreveAssinatura("", null);
        $this->oPdf->showPDF("2020_BalancoPatrimonialDCASP_" . time());
    }
}
