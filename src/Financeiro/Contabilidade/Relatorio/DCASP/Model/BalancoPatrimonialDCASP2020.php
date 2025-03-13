<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model;

class BalancoPatrimonialDCASP2020 extends BalancoPatrimonialDCASP2019
{
    /**
     * @var integer
     */
    const CODIGO_RELATORIO = 243;

    const QUADRO_PRINCIPAL_INICIAL = 1;
    const QUADRO_PRINCIPAL_INICIO_PASSIVOS = 17;
    const QUADRO_PRINCIPAL_FINAL = 45;

    const QUADRO_ATIVOS_PASSIVOS_INICIAL = 46;
    const QUADRO_ATIVOS_PASSIVOS_FINAL = 62;

    const QUADRO_CONTAS_COMPENSACAO_INICIAL = 63;
    const QUADRO_CONTAS_COMPENSACAO_FINAL = 74;

    /**
     * Linhas que não devem serem impressa no pdf
     * @var int[]
     */
    protected $linhasOcultarImpressao = [52, 53, 54, 55, 56, 57, 59, 60];


    public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo)
    {
        parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
        $linhas = [8, 15, 16, 25, 34, 44, 45, 49, 61, 62, 68, 74];
        $this->aLinhasTotalizadoras = $linhas;
        $this->linhas =  array(
            'balanco_patrimonial' => array(
                'inicio' => static::QUADRO_ATIVOS_PASSIVOS_INICIAL,
                'final' => static::QUADRO_ATIVOS_PASSIVOS_FINAL
            ),
            'contas_compensacao' => array(
                'inicio' => static::QUADRO_CONTAS_COMPENSACAO_INICIAL,
                'final' => static::QUADRO_CONTAS_COMPENSACAO_FINAL,
            )
        );
    }

    public function emitir()
    {
        $this->preparaCabecalhos();

        $this->aDados = $this->getDados();
        $this->processarQuadros();
        $this->aQuadroSuperavitDeficit = $this->getSuperavitDeficit();

        $this->configurarPdf();

        $this->processarFormasDasLinhas(array(58, 51, 50, 61, 62, 63, 68, 69, 74));

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
